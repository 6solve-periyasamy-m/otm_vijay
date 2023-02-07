@php /** @var \App\Models\Flight\Flight $flight */ @endphp
@section('footer-script')
<script type="text/javascript">
    $(document).ready(function () { $('#flightInventory').DataTable({fixedHeader: true}); });
</script>
@endsection
@can('create', \App\Models\Flight\FlightInventory::class)
<div class="card">
    <div class="card-body">
        {{--<a href="#" class="btn btn-success float-end">Bulk Add Inventory</a>--}}
        <a href="{{ route('flight-inventories.create', ['flight' => $flight, ]) }}" class="btn btn-primary float-end me-1">
            {{ Icon::create() }}
            <span>Add Inventory</span>
        </a>
    </div>
</div>
@endcan
<div class="card">
    <div class="card-body">
        <table id="flightInventory" style="width: 100%;" class="table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Flight Number</th>
                <th scope="col">Travel Class</th>
                <th scope="col">Check In Time</th>
                <th scope="col">Departure Time</th>
                <th scope="col">Arrival Time</th>
                <th scope="col">FIT Selectable</th>
                <th scope="col">Stock</th>
                <th scope="col">Purchase Price</th>
                <th scope="col">Sales Price</th>
                <th scope="col">Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($flight->flightInventory as $flightInventory)
                <tr>
                    <td>{{ $flightInventory->flight_number }}</td>
                    <td>{{ $flightInventory->travelClass->name }}</td>
                    <td data-sort="{{$flightInventory->check_in->unix()}}">{{ f_datetime($flightInventory->check_in) }}</td>
                    <td data-sort="{{$flightInventory->departs_at->unix()}}">{{ f_datetime($flightInventory->departs_at) }}</td>
                    <td data-sort="{{$flightInventory->arrives_at->unix()}}">{{ f_datetime($flightInventory->arrives_at) }}</td>
                    <td>
                        <input type="checkbox" disabled @if($flightInventory->fit_selectable == 1) checked @endif>
                    </td>
                    <td>
                        {{$flightInventory->stock - $flightInventory->used_stock}}/{{ $flightInventory->stock }}<br/>
                        ({{$flightInventory->used_stock}} Sold)
                    </td>
                    <td>{{ f_currency($flightInventory->purchase_price) }}</td>
                    <td>{{ f_currency($flightInventory->sales_price) }}</td>
                    <td>{{ $flightInventory->notes }}</td>
                    <td class="actions-3">
                        @can('create', \App\Models\Flight\FlightInventory::class)
                            <a href="{{route('flight-inventories.duplicate', ['flight' => $flight, 'flightInventory' => $flightInventory,])}}" class="btn btn-outline-blue btn-sm mb-1">
                                {{ Icon::copy() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::copy() }}
                            </span>
                        @endcan
                        @can('update', \App\Models\Flight\FlightInventory::class)
                            <a href="{{route('flight-inventories.edit', ['flight' => $flight, 'flightInventory' => $flightInventory,])}}"
                               class="btn btn-outline-success btn-sm mb-1">
                                {{ Icon::edit() }}
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                {{ Icon::edit() }}
                            </span>
                        @endcan
                        @can('delete', \App\Models\Flight\FlightInventory::class)
                            <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                               onclick="event.preventDefault();document.getElementById('flightInventory-{{ $flightInventory->id }}-delete').submit();">
                                {{ Icon::delete() }}
                            </a>
                            <form id="flightInventory-{{ $flightInventory->id }}-delete"
                                  action="{{ route('flight-inventories.delete', ['flight' => $flight, 'flightInventory' => $flightInventory,]) }}" method="POST"
                                  style="display: none;">{{ csrf_field() }}</form>
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
