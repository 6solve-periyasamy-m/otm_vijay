@php /** @var \App\Models\Accommodation\Accommodation $accommodation */ @endphp
@section('footer-script')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#accommodationInventory').DataTable({fixedHeader: true});
        });
    </script>
@endsection
@can('create', \App\Models\Accommodation\AccommodationInventory::class)
    <div class="card">
        <div class="card-body ">
            {{--<a href="#" class="btn btn-success float-end">Bulk Add Inventory</a>--}}
            <a href="{{ route('accommodation-inventories.create', ['accommodation' => $accommodation, ]) }}"
               class="btn btn-primary float-end me-1">
                {{ Icon::create() }}
                <span>Add Inventory</span>
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-body">
        <table id="accommodationInventory" style="width: 100%;" class="table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Room Type</th>
                <th scope="col">Board Type</th>
                <th scope="col">Check In Time</th>
                <th scope="col">Check Out Time</th>
                <th scope="col">FIT Selectable</th>
                <th scope="col">Stock</th>
                <th scope="col">Purchase Price</th>
                <th scope="col">Sales Price</th>
                <th scope="col">Internal Notes</th>
                <th scope="col">External Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($accommodation->inventory as $inventory)
                <tr>
                    <td>{{ $inventory->roomType->name }}</td>
                    <td>{{ $inventory->boardType->name }}</td>
                    <td data-sort="{{$inventory->check_in->unix()}}">
                        {{ f_datetime($inventory->check_in) }}&nbsp
                        <input type="checkbox" disabled
                               @if($inventory->check_in_time_confirmed == 1) checked @endif>
                    </td>
                    <td data-sort="{{$inventory->check_out->unix()}}">
                        {{ f_datetime($inventory->check_out) }}&nbsp
                        <input type="checkbox" disabled
                               @if($inventory->check_out_time_confirmed == 1) checked @endif>
                    </td>
                    <td>
                        <input type="checkbox" disabled
                               @if($inventory->fit_selectable == 1) checked @endif>
                    </td>
                    <td>
                        {{$inventory->stock - $inventory->used_stock}}
                        /{{ $inventory->stock }}<br/>
                        ({{$inventory->used_stock}} Sold)
                    </td>
                    <td>{{ f_currency($inventory->purchase_price) }}</td>
                    <td>{{ f_currency($inventory->sales_price) }}</td>
                    <td>{{ $inventory->internal_notes }}</td>
                    <td>{{ $inventory->external_notes }}</td>
                    <td class="actions-4">
                        @can('read', \App\Models\Accommodation\AccommodationInventory::class)
                            <a href="{{route('accommodation-inventories.rooming', ['accommodation' => $accommodation, 'accommodationInventory' => $inventory,])}}"
                               class="btn btn-outline-secondary btn-sm mb-1">
                                {{ Icon::list() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::list() }}
                            </span>
                        @endcan
                        @can('create', \App\Models\Accommodation\AccommodationInventory::class)
                            <a href="{{route('accommodation-inventories.duplicate', ['accommodation' => $accommodation, 'accommodationInventory' => $inventory,])}}"
                               class="btn btn-outline-blue btn-sm mb-1">
                                {{ Icon::copy() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::copy() }}
                            </span>
                        @endcan
                        @can('update', \App\Models\Accommodation\AccommodationInventory::class)
                            <a href="{{route('accommodation-inventories.edit', ['accommodation' => $accommodation, 'accommodationInventory' => $inventory,])}}"
                               class="btn btn-outline-success btn-sm mb-1">
                                {{ Icon::edit() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::edit() }}
                            </span>
                        @endcan
                        @can('delete', \App\Models\Accommodation\AccommodationInventory::class)
                            <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                               onclick="event.preventDefault();document.getElementById('accommodationInventory-{{ $inventory->id }}-delete').submit();">
                                {{ Icon::delete() }}
                            </a>
                            <form id="accommodationInventory-{{ $inventory->id }}-delete"
                                  action="{{ route('accommodation-inventories.delete', ['accommodation' => $accommodation, 'accommodationInventory' => $inventory,]) }}"
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
