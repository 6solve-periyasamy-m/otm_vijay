<script type="text/javascript">
    $(document).ready(function () { $('#activityInventory').DataTable({fixedHeader: true}); });
</script>
<a href="{{ route('activity-inventories.create', ['activity' => $activity, ]) }}" class="btn btn-primary">Add Inventory</a>
<a href="#" class="btn btn-success">Bulk Add Inventory</a>
<table id="activityInventory" style="width: 100%;" class="table table-striped">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Ticket Type</th>
        <th scope="col">Start Time</th>
        <th scope="col">End Time</th>
        <th scope="col">Fit Selectable</th>
        <th scope="col">Stock</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Sales Price</th>
        <th scope="col">Currency</th>
        <th scope="col">Notes</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    @foreach($activity->activityInventory as $activityInventory)
        <tr>
            <td>{{ $activityInventory->ticketType->name }}</td>
            <td>{{ $activityInventory->activity_start_date_time }}</td>
            <td>{{ $activityInventory->activity_end_date_time }}</td>
            <td>{{ $activityInventory->fit_selectable ? "Yes" : "No" }}</td>
            <td>{{ $activityInventory->stock }}</td>
            <td>{{ $activityInventory->purchase_price }}</td>
            <td>{{ $activityInventory->sales_price }}</td>
            <td>{{ $activityInventory->currency }}</td>
            <td>{{ $activityInventory->notes }}</td>
            <td>
                <a href="{{route('activity-inventories.edit', ['activity' => $activity, 'activityInventory' => $activityInventory,])}}">
                    <ion-icon name="create"></ion-icon>
                </a>
                <a href="#"
                   onclick="event.preventDefault();document.getElementById('activityInventory-{{ $activityInventory->id }}-delete').submit();">
                    <ion-icon name="trash"></ion-icon>
                </a>
                <form id="activityInventory-{{ $activityInventory->id }}-delete"
                      action="{{ route('activity-inventories.delete', ['activity' => $activity, 'activityInventory' => $activityInventory,]) }}"
                      method="POST" style="display: none;">{{ csrf_field() }}</form>
            </td>
        </tr>
    @endforeach
</table>
