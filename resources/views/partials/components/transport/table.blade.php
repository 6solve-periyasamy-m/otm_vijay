@section('footer-script')
<script type="text/javascript">
$(document).ready(function() {
    $('#transportInventory').DataTable({
        fixedHeader: true
    });
});
</script>
@endsection
@can('create', \App\Models\TransportInventory::class)
<div class="card">
    <div class="card-body">
        {{--<a href="#" class="btn btn-success float-end">Bulk Add Inventory</a>--}}
        <a href="{{ route('transport-inventories.create', ['transport' => $transport, ]) }}" class="btn btn-primary float-end me-1">
            <i class="icon-plus"></i>
            <span>Add Inventory</span>
        </a>
    </div>
</div>
@endcan
<div class="card">
    <div class="card-body">
        <table id="transportInventory" style="width: 100%;" class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Travel Class</th>
                    <th scope="col">Departure Date Time</th>
                    <th scope="col">Arrival Date Time</th>
                    <th scope="col">Fit Selectable</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Purchase Price</th>
                    <th scope="col">Sales Price</th>
                    <th scope="col">Notes</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            @foreach($transport->transportInventory as $transportInventory)
            <tr>
                <td>{{ $transportInventory->travelClass->name }}</td>
                <td>
                    {{ StringFormatter::formatDateTime($transportInventory->departs_at) }}&nbsp
                    <input type="checkbox" disabled @if($transportInventory->departure_time_confirmed == 1) checked @endif>
                </td>
                <td>
                    {{ StringFormatter::formatDateTime($transportInventory->arrives_at) }}
                    <input type="checkbox" disabled @if($transportInventory->arrival_time_confirmed == 1) checked @endif>
                </td>
                <td>
                    <input type="checkbox" disabled @if($flightInventory->fit_selectable == 1) checked @endif>
                </td>
                <td>{{ $transportInventory->stock }}</td>
                <td>{{ StringFormatter::formatCurrency($transportInventory->purchase_price) }}</td>
                <td>{{ StringFormatter::formatCurrency($transportInventory->sales_price) }}</td>
                <td>{{ $transportInventory->notes }}</td>
                <td class="actions-3">
                    @can('create', \App\Models\TransportInventory::class)
                        <a href="{{route('transport-inventories.duplicate', ['transport' => $transport, 'transportInventory' => $transportInventory,])}}" class="btn btn-outline-blue btn-sm mb-1">
                            <i class="icon-layers"></i>
                        </a>
                    @else
                        <span class="btn btn-outline-dark btn-sm mb-1">
                            <i class="icon-layers"></i>
                        </span>
                    @endcan
                    @can('update', \App\Models\TransportInventory::class)
                        <a href="{{route('transport-inventories.edit', ['transport' => $transport, 'transportInventory' => $transportInventory,])}}"
                           class="btn btn-sm btn-outline-success mb-1">
                            <i class="icon-note"></i>
                        </a>
                    @else
                        <span class="btn btn-outline-dark btn-sm mb-1">
                            <i class="icon-note"></i>
                        </span>
                    @endcan
                    @can('delete', \App\Models\TransportInventory::class)
                        <a href="#" class="btn btn-sm btn-outline-danger mb-1"
                           onclick="event.preventDefault();document.getElementById('transportInventory-{{ $transportInventory->id }}-delete').submit();">
                            <i class="icon-trash"></i>
                        </a>
                        <form id="transportInventory-{{ $transportInventory->id }}-delete"
                              action="{{ route('transport-inventories.delete', ['transport' => $transport, 'transportInventory' => $transportInventory,]) }}"
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
