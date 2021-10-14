@extends('layout.main')

@section('content')
<script type="text/javascript">
    let table;
    $(document).ready(function () {
        table = $('.transport-inventory-table').DataTable({
            fixedHeader: true,
            select: { style: "multi+shift" },
            "ajax": "{{ route('api.transport-inventory.datatables', ['tour' => $tour,]) }}",
            "columns": [
                { "data": "name" },
                { "data": "transport_type" },
                { "data": "operator_name" },
                { "data": "departure_location" },
                { "data": "departure_date" },
                { "data": "arrival_location" },
                { "data": "arrival_date" },
                { "data": "is_domestic" },
                { "data": "fit_selectable" },
                { "data": "stock" },
                { "data": "purchase_price" },
                { "data": "sales_price" },
                { "data": "notes" },
            ]
        });
    });
    function getSelectedTransportInventory() {
        let ids = [];
        table.rows({ selected: true, }).every((rowIdx, tableLoop, rowLoop) => {
            let row = table.row(rowIdx);
            ids.push(row.data().id);
        });
        $.ajax({
            type: "POST",
            url: "{{ route('api.tour.transport.inventory.add', ['tour' => $tour,]) }}",
            dataType: "json",
            statusCode: {
                200: function () { alert('Components added successfully'); table.ajax.reload(); },
                400: function () { alert('An incorrect component type has been provided'); }
            },
            data: { "type": $(".transport-component-type-select").find(":selected").val(), "ids": ids },
        });
    }
</script>
<select class="form-select transport-component-type-select">
    <option value="Included" selected>Included</option>
    <option value="Upgrade">Upgrade</option>
    <option value="Add-on">Add-on</option>
</select>
<a href="javascript:getSelectedTransportInventory()" class="btn btn-success">Add Components</a>
<table style="width: 100%;" class="table table-striped transport-inventory-table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Name</th>
        <th scope="col">Transport Type</th>
        <th scope="col">Operator</th>
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
@endsection
