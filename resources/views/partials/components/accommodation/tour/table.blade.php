@extends('layout.main')

@section('content')
<script type="text/javascript">
    let table;
    $(document).ready(function () {
        table = $('.accommodation-inventory-table').DataTable({
            fixedHeader: true,
            select: { style: "multi+shift" },
            "ajax": "{{ route('api.accommodation-inventory.datatables', ['tour' => $tour,]) }}",
            "columns": [
                { "data": "accommodation_name" },
                { "data": "location" },
                { "data": "room_type" },
                { "data": "board_type" },
                { "data": "check_in_time" },
                { "data": "check_in_confirmed" },
                { "data": "check_out_time" },
                { "data": "check_out_confirmed" },
                { "data": "fit_selectable" },
                { "data": "stock" },
                { "data": "purchase_price" },
                { "data": "sales_price" },
                { "data": "notes" },
            ]
        });
    });
    function getSelectedAccommodationInventory() {
        let ids = [];
        table.rows({ selected: true, }).every((rowIdx, tableLoop, rowLoop) => {
            let row = table.row(rowIdx);
            ids.push(row.data().id);
        });
        $.ajax({
            type: "POST",
            url: "{{ route('api.tour.accommodation.inventory.add', ['tour' => $tour,]) }}",
            dataType: "json",
            statusCode: {
                200: function () { alert('Components added successfully'); table.ajax.reload(); },
                400: function () { alert('An incorrect component type has been provided'); }
            },
            data: { "type": $(".component-type-select").find(":selected").val(), "ids": ids },
        });
    }
</script>
<select class="form-select component-type-select">
    <option value="Included" selected>Included</option>
    <option value="Upgrade">Upgrade</option>
    <option value="Add-on">Add-on</option>
</select>
<a href="javascript:getSelectedAccommodationInventory()" class="btn btn-success">Add Components</a>
<table style="width: 100%;" class="table table-striped accommodation-inventory-table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Accommodation</th>
        <th scope="col">Location</th>
        <th scope="col">Room Type</th>
        <th scope="col">Board Type</th>
        <th scope="col">Check In</th>
        <th scope="col">Confirmed</th>
        <th scope="col">Check Out</th>
        <th scope="col">Confirmed</th>
        <th scope="col">Fit Selectable</th>
        <th scope="col">Stock</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Sales Price</th>
        <th scope="col">Notes</th>
    </tr>
    </thead>
</table>
@endsection
