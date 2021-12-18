@section('footer-script')
<script type="text/javascript">
    $(document).ready(function () { $('#activityInventory').DataTable({fixedHeader: true}); });
</script>
@endsection
@can('create', \App\Models\ActivityInventory::class)
<div class="card">
    <div class="card-body">
        {{--<a href="#" class="btn btn-success float-end">Bulk Add Inventory</a>--}}
        <a href="{{ route('activity-inventories.create', ['activity' => $activity, ]) }}" class="btn btn-primary float-end">
            <i class="icon-plus"></i>
            <span>Add Inventory</span>
        </a>
    </div>
</div>
@endcan
<div class="card">
    <div class="card-body">
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
                <th scope="col">Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($activity->activityInventory as $activityInventory)
                <tr>
                    <td>{{ $activityInventory->ticketType->name }}</td>
                    <td>{{ StringFormatter::formatDateTime($activityInventory->starts_at) }}</td>
                    <td>{{ StringFormatter::formatDateTime($activityInventory->ends_at) }}</td>
                    <td>
                        <input type="checkbox" disabled @if($activityInventory->fit_selectable == 1) checked @endif>
                    </td>
                    <td>{{ $activityInventory->stock }}</td>
                    <td>{{ StringFormatter::formatCurrency($activityInventory->purchase_price) }}</td>
                    <td>{{ StringFormatter::formatCurrency($activityInventory->sales_price) }}</td>
                    <td>{{ $activityInventory->notes }}</td>
                    <td class="actions-3">
                        @can('create', \App\Models\ActivityInventory::class)
                            <a href="{{route('activity-inventories.duplicate', ['activity' => $activity, 'activityInventory' => $activityInventory,])}}" class="btn btn-outline-blue btn-sm mb-1">
                                <i class="icon-layers"></i>
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                <i class="icon-layers"></i>
                            </span>
                        @endcan
                        @can('update', \App\Models\ActivityInventory::class)
                            <a href="{{route('activity-inventories.edit', ['activity' => $activity, 'activityInventory' => $activityInventory,])}}"
                               class="btn btn-outline-success btn-sm mb-1">
                                <i class="icon-note"></i>
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                <i class="icon-note"></i>
                            </span>
                        @endcan
                        @can('delete', \App\Models\ActivityInventory::class)
                            <a href="#" class="btn btn-sm btn-outline-danger mb-1"
                               onclick="event.preventDefault();document.getElementById('activityInventory-{{ $activityInventory->id }}-delete').submit();">
                                <i class="icon-trash"></i>
                            </a>
                            <form id="activityInventory-{{ $activityInventory->id }}-delete"
                                  action="{{ route('activity-inventories.delete', ['activity' => $activity, 'activityInventory' => $activityInventory,]) }}"
                                  method="POST" style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                <i class="icon-trash"></i>
                            </span>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
