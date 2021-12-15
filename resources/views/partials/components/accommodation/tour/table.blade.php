<script type="text/javascript">
    let accommodationTable;
    $(document).ready(function () {
        accommodationTable = $('.accommodation-inventory-table').DataTable({
            fixedHeader: true,
            select: { style: "multi+shift" },
            "ajax": {
                "url": "{{ route('api.accommodation-inventory.datatables', ['tour' => $tour,]) }}",
                "data": {
                    "__api_token": "{{ Auth::user()->getCurrentToken()->token }}",
                },
                "type": "post",
            },
            "columns": [
                { "data": "accommodation_name" },
                { "data": "location" },
                { "data": "room_type" },
                { "data": "board_type" },
                { "data": "check_in" },
                { "data": "check_in_time_confirmed" },
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
    @can('create', \App\Models\AccommodationInventoryTour::class)
    function getSelectedAccommodationInventory() {
        let ids = [];
        accommodationTable.rows({ selected: true, }).every((rowIdx, tableLoop, rowLoop) => {
            let row = accommodationTable.row(rowIdx);
            ids.push(row.data().id);
        });
        $.ajax({
            type: "POST",
            url: "{{ route('api.tour.accommodation.inventory.add', ['tour' => $tour,]) }}",
            dataType: "json",
            statusCode: {
                200: function () { alert('Components added successfully'); accommodationTable.ajax.reload(); },
                400: function () { alert('An incorrect component type has been provided'); }
            },
            data: { "type": $(".accommodation-component-type-select").find(":selected").val(), "ids": ids, "__api_token": '{{ Auth::user()->getCurrentToken()->token }}', },
        });
    }
    @endcan
</script>
@can('create', \App\Models\AccommodationInventoryTour::class)
<div class="d-flex justify-content-between mb-3">
    <select class="form-select accommodation-component-type-select">
        <option value="Included" selected>Included</option>
        <option value="Upgrade">Upgrade</option>
        <option value="Add-on">Add-on</option>
    </select>
    <a href="javascript:getSelectedAccommodationInventory()" class="btn btn-primary ms-3 text-white">
        <i class="icon-plus"></i>
        <span>Add Components</span>
    </a>
</div>
@endcan
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
