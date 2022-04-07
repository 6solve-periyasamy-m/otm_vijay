<script type="text/javascript">
    let activityTable;
    $(document).ready(function () {
        activityTable = $('.activity-inventory-table').DataTable({fixedHeader: true,select: { style: "multi+shift" },});
    });
    @can('create', \App\Models\Activity\ActivityInventoryTour::class)
    function getSelectedActivityInventory() {
        let ids = [];
        activityTable.rows({ selected: true, }).every((rowIdx, tableLoop, rowLoop) => {
            let row = activityTable.row(rowIdx);
            ids.push($(row.node()).attr('inventory_id'));
        });
        if (ids.length <= 0) return alert('No components are selected');
        $.ajax({
            type: "POST",
            url: "{{ route('api.tour.activity.inventory.add', ['tour' => $tour,]) }}",
            dataType: "json",
            statusCode: {
                200: function () { alert('Components added successfully'); location.reload(); },
                400: function () { alert('An incorrect component type has been provided'); },
                403: function () { alert('Authentication has expired. Please refresh the page'); }
            },
            data: { "type": $(".activity-component-type-select").find(":selected").val(), "ids": ids, "__api_token": '{{ Auth::user()->getCurrentToken()->token }}', },
        });
    }
    @endcan
</script>
@can('create', \App\Models\Activity\ActivityInventoryTour::class)
<div class="d-flex justify-content-between mb-3">
    <select class="form-select activity-component-type-select">
        <option value="Included" selected>Included</option>
        <option value="Add-on">Add-on</option>
    </select>    
    <a href="javascript:getSelectedActivityInventory()" class="btn btn-primary ms-3 text-white">
        <i class="icon-plus"></i>
        <span>Add Selected Rows</span>
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
        <th scope="col">FIT Selectable</th>
        <th scope="col">Stock</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Sales Price</th>
        <th scope="col">Notes</th>
    </tr>
    </thead>
    @foreach(\App\Repository\ActivityComponentRepository::getAvailableBetweenDates($tour, $tour->date_from, $tour->date_to->setTime(23, 59, 59)) as $inventory)
        <tr inventory_id="{{ $inventory->id }}">
            <td>{{ $inventory->component->name }}</td>
            <td>{{ $inventory->component->address->region . ' - ' . $inventory->component->address->country->name }}</td>
            <td>{{ $inventory->component->activityType }}</td>
            <td>{{ $inventory->ticketType }}</td>
            <td>
                {{ StringFormatter::formatDateTime($inventory->starts_at) }}&nbsp
            </td>
            <td>
                {{ StringFormatter::formatDateTime($inventory->ends_at) }}&nbsp
            </td>
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
