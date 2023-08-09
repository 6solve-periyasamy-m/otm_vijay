@php /** @var \App\Models\Activity\Activity $activity */ @endphp
@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#activityInventory').DataTable({fixedHeader: true});
        });
    </script>
@endsection
@can('create', \App\Models\Activity\ActivityInventory::class)
    <div class="card">
        <div class="card-body">
            {{--<a href="#" class="btn btn-success float-end">Bulk Add Inventory</a>--}}
            <a href="{{ route('activity-inventories.create', ['activity' => $activity, ]) }}"
               class="btn btn-primary float-end">
                {{ Icon::create() }}
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
                <th scope="col">Internal Notes</th>
                <th scope="col">External Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($activity->activityInventory as $inventory)
                <tr>
                    <td>{{ $inventory->ticketType->name }}</td>
                    <td data-sort="{{$inventory->starts_at->unix()}}">{{ f_datetime($inventory->starts_at) }}</td>
                    <td data-sort="{{$inventory->ends_at->unix()}}">{{ f_datetime($inventory->ends_at) }}</td>
                    <td>
                        <input type="checkbox" disabled @if($inventory->fit_selectable == 1) checked @endif>
                    </td>
                    <td>
                        {{$inventory->stock - $inventory->used_stock}}/{{ $inventory->stock }}
                        <br/>
                        ({{$inventory->used_stock}} Sold)
                    </td>
                    <td>{{ f_currency($inventory->purchase_price) }}</td>
                    <td>{{ f_currency($inventory->sales_price) }}</td>
                    <td>{{ $inventory->internal_notes }}</td>
                    <td>{{ $inventory->external_notes }}</td>
                    <td class="actions-4">
                        @can('read', \App\Models\Activity\ActivityInventory::class)
                            <a href="{{route('activity-inventories.manifest.view', ['activity' => $activity, 'activityInventory' => $inventory,])}}"
                               class="btn btn-outline-secondary btn-sm mb-1">
                                {{ Icon::list() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::list() }}
                            </span>
                        @endcan
                        @can('create', \App\Models\Activity\ActivityInventory::class)
                            <a href="{{route('activity-inventories.duplicate', ['activity' => $activity, 'activityInventory' => $inventory,])}}"
                               class="btn btn-outline-blue btn-sm mb-1">
                                {{ Icon::copy() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::copy() }}
                            </span>
                        @endcan
                        @can('update', \App\Models\Activity\ActivityInventory::class)
                            <a href="{{route('activity-inventories.edit', ['activity' => $activity, 'activityInventory' => $inventory,])}}"
                               class="btn btn-outline-success btn-sm mb-1">
                                {{ Icon::edit() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::edit() }}
                            </span>
                        @endcan
                        @can('delete', \App\Models\Activity\ActivityInventory::class)
                            <a href="#" class="btn btn-sm btn-outline-danger mb-1"
                               onclick="event.preventDefault();document.getElementById('activityInventory-{{ $inventory->id }}-delete').submit();">
                                {{ Icon::delete() }}
                            </a>
                            <form id="activityInventory-{{ $inventory->id }}-delete"
                                  action="{{ route('activity-inventories.delete', ['activity' => $activity, 'activityInventory' => $inventory,]) }}"
                                  method="POST" style="display: none;">{{ csrf_field() }}</form>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::delete() }}
                            </span>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
