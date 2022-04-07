<script type="text/javascript">
    let flightTable;
    $(document).ready(function () {
        flightTable = $('.flight-inventory-table').DataTable({
            fixedHeader: true,
            select: { style: "multi+shift" },
        });
    });
    @can('create', \App\Models\Flight\FlightInventoryTour::class)
    function getSelectedFlightInventory() {
        let ids = [];
        flightTable.rows({ selected: true, }).every((rowIdx, tableLoop, rowLoop) => {
            let row = flightTable.row(rowIdx);
            ids.push($(row.node()).attr('inventory_id'));
        });
        if (ids.length <= 0) return alert('No components are selected');
        $.ajax({
            type: "POST",
            url: "{{ route('api.tour.flight.inventory.add', ['tour' => $tour,]) }}",
            dataType: "json",
            statusCode: {
                200: function () { alert('Components added successfully'); location.reload(); },
                400: function () { alert('An incorrect component type has been provided'); },
                403: function () { alert('Authentication has expired. Please refresh the page'); }
            },
            data: { "type": $(".flight-component-type-select").find(":selected").val(),
                "direction": $(".flight-direction-select").find(":selected").val(),
                "ids": ids, "__api_token": '{{ Auth::user()->getCurrentToken()->token }}', },
        });
    }
    @endcan
</script>
@can('create', \App\Models\Flight\FlightInventoryTour::class)
<div class="d-flex justify-content-between mb-3">
    <div class="d-inline-flex col-12 col-xl-10">
        <select class="form-select flight-component-type-select">
            <option value="Included" selected>Included</option>
            <option value="Add-on">Add-on</option>
        </select>
        <select class="form-select flight-direction-select">
            <option value="Outbound" selected>Outbound</option>
            <option value="Inbound">Inbound</option>
        </select>
    </div>
    <a href="javascript:getSelectedFlightInventory()" class="btn btn-primary ms-3 text-white">
        <i class="icon-plus"></i>
        <span>Add Selected Rows</span>
    </a>
</div>
@endcan
<table style="width: 100%;" class="table table-striped flight-inventory-table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Flight Number</th>
        <th scope="col">Travel Class</th>
        <th scope="col">Departure Airport</th>
        <th scope="col">Departure Time</th>
        <th scope="col">Arrival Airport</th>
        <th scope="col">Arrival Time</th>
        <th scope="col">Domestic</th>
        <th scope="col">FIT Selectable</th>
        <th scope="col">Stock</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Sales Price</th>
        <th scope="col">Notes</th>
    </tr>
    </thead>
    @foreach(\App\Repository\FlightComponentRepository::getAvailableBetweenDates($tour, $tour->date_from, $tour->date_to->setTime(23, 59, 59)) as $inventory)
        <tr inventory_id="{{ $inventory->id }}">
            <td>{{ $inventory->flight_number }}</td>
            <td>{{ $inventory->travelClass }}</td>
            <td>{{ $inventory->component->departureAirport }}</td>
            <td>{{ StringFormatter::formatDateTime($inventory->departs_at) }}</td>
            <td>{{ $inventory->component->arrivalAirport }}</td>
            <td>{{ StringFormatter::formatDateTime($inventory->arrives_at) }}</td>
            <td>{{ StringFormatter::formatBoolean($inventory->component->is_domestic) }}</td>
            <td>
                <input type="checkbox" disabled @if($inventory->fit_selectable == 1) checked @endif>
            </td>
            <td>
                {{$inventory->stock - $inventory->getUsedStock()}}/{{ $inventory->stock }}<br/>
                ({{$inventory->getUsedStock()}} Sold)
            </td>
            <td>{{ StringFormatter::formatCurrency($inventory->purchase_price) }}</td>
            <td>{{ StringFormatter::formatCurrency($inventory->sales_price) }}</td>
            <td>{{ $inventory->notes }}</td>
        </tr>
    @endforeach
</table>
