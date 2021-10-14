<script type="text/javascript">
    let flightTable;
    $(document).ready(function () {
        flightTable = $('.flight-inventory-table').DataTable({
            fixedHeader: true,
            select: { style: "multi+shift" },
            "ajax": "{{ route('api.flight-inventory.datatables', ['tour' => $tour,]) }}",
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
            data: { "type": $(".flight-component-type-select").find(":selected").val(), "ids": ids },
        });
    }
</script>
<select class="form-select flight-component-type-select">
    <option value="Included" selected>Included</option>
    <option value="Upgrade">Upgrade</option>
    <option value="Add-on">Add-on</option>
</select>
<a href="javascript:getSelectedFlightInventory()" class="btn btn-success">Add Components</a>
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
