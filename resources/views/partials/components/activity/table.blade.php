@php /** @var \App\Models\Activity\Activity $activity */ @endphp
@section('footer-script')
<script type="text/javascript">
    $(document).ready(function () { $('#activityInventory').DataTable({fixedHeader: true}); });
</script>
@endsection
@can('create', \App\Models\Activity\ActivityInventory::class)
<div class="card">
    <div class="card-body">
        {{--<a href="#" class="btn btn-success float-end">Bulk Add Inventory</a>--}}
        <a href="{{ route('activity-inventories.create', ['activity' => $activity, ]) }}" class="btn btn-primary float-end">
            <x-icon icon="plus" />
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
                <th scope="col">FIT Selectable</th>
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
                    <td data-sort="{{$activityInventory->starts_at->unix()}}">{{ f_datetime($activityInventory->starts_at) }}</td>
                    <td data-sort="{{$activityInventory->ends_at->unix()}}">{{ f_datetime($activityInventory->ends_at) }}</td>
                    <td>
                        <input type="checkbox" disabled @if($activityInventory->fit_selectable == 1) checked @endif>
                    </td>
                    <td>
                        {{$activityInventory->stock - $activityInventory->used_stock}}/{{ $activityInventory->stock }}<br/>
                        ({{$activityInventory->used_stock}} Sold)
                    </td>
                    <td>{{ f_currency($activityInventory->purchase_price) }}</td>
                    <td>{{ f_currency($activityInventory->sales_price) }}</td>
                    <td>{{ $activityInventory->notes }}</td>
                    <td class="actions-3">
                        @can('create', \App\Models\Activity\ActivityInventory::class)
                            <a href="{{route('activity-inventories.duplicate', ['activity' => $activity, 'activityInventory' => $activityInventory,])}}" class="btn btn-outline-blue btn-sm mb-1">
                                <x-icon icon="layers" />
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                <x-icon icon="layers" />
                            </span>
                        @endcan
                        @can('update', \App\Models\Activity\ActivityInventory::class)
                            <a href="{{route('activity-inventories.edit', ['activity' => $activity, 'activityInventory' => $activityInventory,])}}"
                               class="btn btn-outline-success btn-sm mb-1">
                                <x-icon icon="note" />
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                <x-icon icon="note" />
                            </span>
                        @endcan
                        @can('delete', \App\Models\Activity\ActivityInventory::class)
                            <a href="#" class="btn btn-sm btn-outline-danger mb-1"
                               onclick="event.preventDefault();document.getElementById('activityInventory-{{ $activityInventory->id }}-delete').submit();">
                                <x-icon icon="trash" />
                            </a>
                            <form id="activityInventory-{{ $activityInventory->id }}-delete"
                                  action="{{ route('activity-inventories.delete', ['activity' => $activity, 'activityInventory' => $activityInventory,]) }}"
                                  method="POST" style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                <x-icon icon="trash" />
                            </span>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
