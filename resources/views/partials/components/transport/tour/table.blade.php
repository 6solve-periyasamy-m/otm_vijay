<script type="text/javascript">
    let transportTable;
    $(document).ready(function () {
        transportTable = $('.transport-inventory-table').DataTable({
            fixedHeader: true,
            select: { style: "multi+shift" },
        });
    });
    @can('create', \App\Models\Transport\TransportInventoryTour::class)
    function getSelectedTransportInventory() {
        let ids = [];
        transportTable.rows({ selected: true, }).every((rowIdx, tableLoop, rowLoop) => {
            let row = transportTable.row(rowIdx);
            ids.push($(row.node()).attr('inventory_id'));
        });
        if (ids.length <= 0) return alert('No components are selected');
        $.ajax({
            type: "POST",
            url: "{{ route('api.tour.transport.inventory.add', ['tour' => $tour,]) }}",
            dataType: "json",
            statusCode: {
                200: function () { alert('Components added successfully'); location.reload(); },
                400: function () { alert('An incorrect component type has been provided'); },
                403: function () { alert('Authentication has expired. Please refresh the page'); }
            },
            data: { "type": $(".transport-component-type-select").find(":selected").val(), "ids": ids, "__api_token": '{{ Auth::user()->getCurrentToken()->token }}', },
        });
    }
    @endcan
</script>
@can('create', \App\Models\Transport\TransportInventoryTour::class)
    @include('partials.components.add-bar', ['component' => 'transport', 'href' => 'javascript:getSelectedTransportInventory()'])
@endcan
<table style="width: 100%;" class="table table-striped transport-inventory-table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Name</th>
        <th scope="col">Transport Number</th>
        <th scope="col">Transport Type</th>
        <th scope="col">Travel Class</th>
        <th scope="col">Operator</th>
        <th scope="col">Departure Location</th>
        <th scope="col">Departure Time</th>
        <th scope="col">Arrival Location</th>
        <th scope="col">Arrival Time</th>
        <th scope="col">Domestic</th>
        <th scope="col">FIT Selectable</th>
        <th scope="col">Stock</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Sales Price</th>
        <th scope="col">Notes</th>
    </tr>
    </thead>
    <tbody>
    @php /** @var \App\Models\Transport\TransportInventory $inventory */ @endphp
    @foreach(\App\Repository\Model\Transport\TransportInventoryRepository::getBetweenDates($tour->date_from, $tour->date_to, $tour->repository) as $inventory)
        <tr inventory_id="{{ $inventory->id }}">
            <td>{{ $inventory->component->name }}</td>
            <td>{{ $inventory->transport_number }}</td>
            <td>{{ $inventory->component->transportType }}</td>
            <td>{{ $inventory->travelClass }}</td>
            <td>{{ $inventory->component->operator }}</td>
            <td>{{ $inventory->component->departureAddress->name }}</td>
            <td data-sort="{{$inventory->departs_at?->unix()}}">{{ f_datetime($inventory->departs_at) }}</td>
            <td>{{ $inventory->component->arrivalAddress->name }}</td>
            <td data-sort="{{$inventory->arrives_at?->unix()}}">{{ f_datetime($inventory->arrives_at) }}</td>
            <td>{{ f_bool($inventory->component->is_domestic) }}</td>
            <td>
                <input type="checkbox" disabled @if($inventory->fit_selectable == 1) checked @endif>
            </td>
            <td>
                {{$inventory->stock - $inventory->used_stock}}/{{ $inventory->stock }}<br/>
                ({{$inventory->used_stock}} Sold)
            </td>
            <td>{{ fr_currency($inventory->purchase_price, $inventory->repository->getCurrency()) }}</td>
            <td>{{ f_currency($inventory->sales_price) }}</td>
            <td>{{ $inventory->internal_notes }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
