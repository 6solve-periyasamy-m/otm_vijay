<script type="text/javascript">
    let flightTable;
    $(document).ready(function () {
        flightTable = $('.flight-inventory-table').DataTable({
            fixedHeader: true,
            select: { style: "multi+shift" },
            "ajax": {
                "url": "{{ route('api.flight-inventory.datatables', ['tour' => $tour,]) }}",
                "data": {
                    "__api_token": "{{ Auth::user()->getCurrentToken()->token }}",
                },
                "type": "post",
            },
            "columns": [
                { "data": "flight_number" },
                { "data": "travel_class" },
                { "data": "departure_airport" },
                { "data": "departure_time" },
                { "data": "arrival_airport" },
                { "data": "arrival_time" },
                { "data": "is_domestic" },
                { "data": "fit_selectable" },
                { "data": "stock" },
                { "data": "purchase_price" },
                { "data": "sales_price" },
                { "data": "notes" },
            ]
        });
    });
    @can('create', \App\Models\FlightInventoryTour::class)
    function getSelectedFlightInventory() {
        let ids = [];
        flightTable.rows({ selected: true, }).every((rowIdx, tableLoop, rowLoop) => {
            let row = flightTable.row(rowIdx);
            ids.push(row.data().id);
        });
        $.ajax({
            type: "POST",
            url: "{{ route('api.tour.flight.inventory.add', ['tour' => $tour,]) }}",
            dataType: "json",
            statusCode: {
                200: function () { alert('Components added successfully'); flightTable.ajax.reload(); },
                400: function () { alert('An incorrect component type has been provided'); }
            },
            data: { "type": $(".flight-component-type-select").find(":selected").val(),
                "direction": $(".flight-direction-select").find(":selected").val(),
                "ids": ids, "__api_token": '{{ Auth::user()->getCurrentToken()->token }}', },
        });
    }
    @endcan
</script>
@can('create', \App\Models\FlightInventoryTour::class)
<div class="d-flex justify-content-between mb-3">
    <div class="d-inline-flex col-12 col-xl-10">
        <select class="form-select flight-component-type-select">
            <option value="Included" selected>Included</option>
            <option value="Upgrade">Upgrade</option>
            <option value="Add-on">Add-on</option>
        </select>
        <select class="form-select flight-direction-select">
            <option value="Outbound" selected>Outbound</option>
            <option value="Inbound">Inbound</option>
        </select>
    </div>
    <a href="javascript:getSelectedFlightInventory()" class="btn btn-primary ms-3 text-white">
        <i class="icon-plus"></i>
        <span>Add Components</span>
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
        <th scope="col">Fit Selectable</th>
        <th scope="col">Stock</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Sales Price</th>
        <th scope="col">Notes</th>
    </tr>
    </thead>
</table>
