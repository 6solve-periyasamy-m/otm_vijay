<script type="text/javascript">
    let transportTable;
    $(document).ready(function () {
        transportTable = $('.transport-inventory-table').DataTable({
            fixedHeader: true,
            select: { style: "multi+shift" },
            "ajax": {
                "url": "{{ route('api.transport-inventory.datatables', ['tour' => $tour,]) }}",
                "data": {
                    "__api_token": "{{ Auth::user()->getCurrentToken()->token }}",
                },
                "type": "post",
            },
            "columns": [
                { "data": "name" },
                { "data": "transport_type" },
                { "data": "travel_class" },
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
    @can('create', \App\Models\TransportInventoryTour::class)
    function getSelectedTransportInventory() {
        let ids = [];
        transportTable.rows({ selected: true, }).every((rowIdx, tableLoop, rowLoop) => {
            let row = transportTable.row(rowIdx);
            ids.push(row.data().id);
        });
        $.ajax({
            type: "POST",
            url: "{{ route('api.tour.transport.inventory.add', ['tour' => $tour,]) }}",
            dataType: "json",
            statusCode: {
                200: function () { alert('Components added successfully'); transportTable.ajax.reload(); },
                400: function () { alert('An incorrect component type has been provided'); }
            },
            data: { "type": $(".transport-component-type-select").find(":selected").val(), "ids": ids, "__api_token": '{{ Auth::user()->getCurrentToken()->token }}', },
        });
    }
    @endcan
</script>
@can('create', \App\Models\TransportInventoryTour::class)
<div class="d-flex justify-content-between mb-3">
    <select class="form-select transport-component-type-select">
        <option value="Included" selected>Included</option>
        <option value="Upgrade">Upgrade</option>
        <option value="Add-on">Add-on</option>
    </select>    
    <a href="javascript:getSelectedTransportInventory()" class="btn btn-primary ms-3 text-white">
        <i class="icon-plus"></i>
        <span>Add Components</span>
    </a>
</div>
@endcan
<table style="width: 100%;" class="table table-striped transport-inventory-table">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Name</th>
        <th scope="col">Transport Type</th>
        <th scope="col">Travel Class</th>
        <th scope="col">Operator</th>
        <th scope="col">Departure Location</th>
        <th scope="col">Departure Time</th>
        <th scope="col">Arrival Location</th>
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
