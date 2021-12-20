<script type="text/javascript">
    let activityTable;
    $(document).ready(function () {
        activityTable = $('.activity-inventory-table').DataTable({
            fixedHeader: true,
            select: { style: "multi+shift" },
            "ajax": {
                "url": "{{ route('api.activity-inventory.datatables', ['tour' => $tour,]) }}",
                "data": {
                    "__api_token": "{{ Auth::user()->getCurrentToken()->token }}",
                },
                "type": "post",
            },
            "columns": [
                { "data": "name" },
                { "data": "location" },
                { "data": "activity_type" },
                { "data": "ticket_type" },
                { "data": "start_date" },
                { "data": "end_date" },
                { "data": "fit_selectable" },
                { "data": "stock" },
                { "data": "purchase_price" },
                { "data": "sales_price" },
                { "data": "notes" },
            ]
        });
    });
    @can('create', \App\Models\ActivityInventoryTour::class)
    function getSelectedActivityInventory() {
        let ids = [];
        activityTable.rows({ selected: true, }).every((rowIdx, tableLoop, rowLoop) => {
            let row = activityTable.row(rowIdx);
            ids.push(row.data().id);
        });
        $.ajax({
            type: "POST",
            url: "{{ route('api.tour.activity.inventory.add', ['tour' => $tour,]) }}",
            dataType: "json",
            statusCode: {
                200: function () { alert('Components added successfully'); activityTable.ajax.reload(); },
                400: function () { alert('An incorrect component type has been provided'); }
            },
            data: { "type": $(".activity-component-type-select").find(":selected").val(), "ids": ids, "__api_token": '{{ Auth::user()->getCurrentToken()->token }}', },
        });
    }
    @endcan
</script>
@can('create', \App\Models\ActivityInventoryTour::class)
<div class="d-flex justify-content-between mb-3">
    <select class="form-select activity-component-type-select">
        <option value="Included" selected>Included</option>
        <option value="Upgrade">Upgrade</option>
        <option value="Add-on">Add-on</option>
    </select>    
    <a href="javascript:getSelectedActivityInventory()" class="btn btn-primary ms-3 text-white">
        <i class="icon-plus"></i>
        <span>Add Components</span>
    </a>
</div>
@endcan
<table style="width: 100%;" class="table table-striped activity-inventory-table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Activity</th>
        <th scope="col">Location</th>
        <th scope="col">Activity Type</th>
        <th scope="col">Ticket Type</th>
        <th scope="col">Start Date</th>
        <th scope="col">End Date</th>
        <th scope="col">Fit Selectable</th>
        <th scope="col">Stock</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Sales Price</th>
        <th scope="col">Notes</th>
    </tr>
    </thead>
</table>
