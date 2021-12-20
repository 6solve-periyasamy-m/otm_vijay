@section('footer-script')
<script type="text/javascript">
    $(document).ready(function () { $('#flightInventory').DataTable({fixedHeader: true}); });
</script>
@endsection
@can('create', \App\Models\FlightInventory::class)
<div class="card">
    <div class="card-body">
        {{--<a href="#" class="btn btn-success float-end">Bulk Add Inventory</a>--}}
        <a href="{{ route('flight-inventories.create', ['flight' => $flight, ]) }}" class="btn btn-primary float-end me-1">
            <i class="icon-plus"></i>
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
                <th scope="col">Fit Selectable</th>
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
                    <td>{{ StringFormatter::formatDateTime($flightInventory->check_in) }}</td>
                    <td>{{ StringFormatter::formatDateTime($flightInventory->departs_at) }}</td>
                    <td>{{ StringFormatter::formatDateTime($flightInventory->arrives_at) }}</td>
                    <td>
                        <input type="checkbox" disabled @if($flightInventory->fit_selectable == 1) checked @endif>
                    </td>
                    <td>{{ $flightInventory->stock }}</td>
                    <td>{{ StringFormatter::formatCurrency($flightInventory->purchase_price) }}</td>
                    <td>{{ StringFormatter::formatCurrency($flightInventory->sales_price) }}</td>
                    <td>{{ $flightInventory->notes }}</td>
                    <td class="actions-3">
                        @can('create', \App\Models\FlightInventory::class)
                            <a href="{{route('flight-inventories.duplicate', ['flight' => $flight, 'flightInventory' => $flightInventory,])}}" class="btn btn-outline-blue btn-sm mb-1">
                                <i class="icon-layers"></i>
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                <i class="icon-layers"></i>
                            </span>
                        @endcan
                        @can('update', \App\Models\FlightInventory::class)
                            <a href="{{route('flight-inventories.edit', ['flight' => $flight, 'flightInventory' => $flightInventory,])}}"
                               class="btn btn-outline-success btn-sm mb-1">
                                <i class="icon-note"></i>
                            </a>
                        @else
                            <span class="btn btn-outline-dark btn-sm mb-1">
                                <i class="icon-note"></i>
                            </span>
                        @endcan
                        @can('delete', \App\Models\FlightInventory::class)
                            <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                               onclick="event.preventDefault();document.getElementById('flightInventory-{{ $flightInventory->id }}-delete').submit();">
                                <i class="icon-trash"></i>
                            </a>
                            <form id="flightInventory-{{ $flightInventory->id }}-delete"
                                  action="{{ route('flight-inventories.delete', ['flight' => $flight, 'flightInventory' => $flightInventory,]) }}" method="POST"
                                  style="display: none;">{{ csrf_field() }}</form>
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
