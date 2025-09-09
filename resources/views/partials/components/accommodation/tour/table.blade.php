<script type="text/javascript">
    let accommodationTable;
    $(document).ready(function () {
        accommodationTable = $('.accommodation-inventory-table').DataTable({fixedHeader: true,select: { style: "multi+shift" }, });
    });
    @can('create', \App\Models\Accommodation\AccommodationInventoryTour::class)
    function getSelectedAccommodationInventory() {
        let ids = [];
        accommodationTable.rows({ selected: true, }).every((rowIdx, tableLoop, rowLoop) => {
            let row = accommodationTable.row(rowIdx);
            ids.push($(row.node()).attr('inventory_id'));
        });
        if (ids.length <= 0) return alert('No components are selected');
        $.ajax({
            type: "POST",
            url: "{{ route('api.tour.accommodation.inventory.add', ['tour' => $tour,]) }}",
            dataType: "json",
            statusCode: {
                200: function () { alert('Components added successfully'); location.reload(); },
                400: function () { alert('An incorrect component type has been provided'); },
                403: function () { alert('Authentication has expired. Please refresh the page'); }
            },
            data: { "type": $(".accommodation-component-type-select").find(":selected").val(), "ids": ids, "__api_token": '{{ Auth::user()->getCurrentToken()->token }}', },
        });
    }
    @endcan
</script>
@can('create', \App\Models\Accommodation\AccommodationInventoryTour::class)
    @include('partials.components.add-bar', ['component' => 'accommodation', 'href' => 'javascript:getSelectedAccommodationInventory()'])
@endcan
<table style="width: 100%;" class="table table-striped accommodation-inventory-table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Accommodation</th>
        <th scope="col">Location</th>
        <th scope="col">Room Type</th>
        <th scope="col">Board Type</th>
        <th scope="col">Check In</th>
        <th scope="col">Check Out</th>
        <th scope="col">FIT Selectable</th>
        <th scope="col">Stock</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Sales Price</th>
        <th scope="col">Notes</th>
    </tr>
    </thead>
    <tbody>
    @foreach(\App\Repository\Model\Accommodation\AccommodationInventoryRepository::getBetweenDates($tour->date_from, $tour->date_to, $tour->repository) as $inventory)
        <tr inventory_id="{{ $inventory->id }}">
            <td>{{ $inventory->component->name }}</td>
            <td>{{ $inventory->component->address }}</td>
            <td>{{ $inventory->roomType }}</td>
            <td>{{ $inventory->boardType }}</td>
            <td>
                {{ f_datetime($inventory->check_in) }}&nbsp
                <input type="checkbox" disabled @if($inventory->check_in_time_confirmed == 1) checked @endif>
            </td>
            <td data-sort="{{$inventory->check_in?->unix()}}">
                {{ f_datetime($inventory->check_out) }}&nbsp
                <input type="checkbox" disabled @if($inventory->check_out_time_confirmed == 1) checked @endif>
            </td>
            <td data-sort="{{$inventory->check_out?->unix()}}">
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
