@section('footer-script')
<script type="text/javascript">
$(document).ready(function() {
    $('#transportInventory').DataTable({
        fixedHeader: true
    });
});
</script>
@endsection

<div class="card">
    <div class="card-body text-end">
        <a href="{{ route('transport-inventories.create', ['transport' => $transport, ]) }}" class="btn btn-primary">
            <i class="icon-plus"></i>
            <span>Add Inventory</span>
        </a>
        <a href="#" class="btn btn-success">Bulk Add Inventory</a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <table id="transportInventory" style="width: 100%;" class="table table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">Travel Class</th>
                    <th scope="col">Departure Date Time</th>
                    <th scope="col">Departure Confirmed</th>
                    <th scope="col">Arrival Date Time</th>
                    <th scope="col">Arrival Confirmed</th>
                    <th scope="col">Fit Selectable</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Purchase Price</th>
                    <th scope="col">Sales Price</th>
                    <th scope="col">Currency</th>
                    <th scope="col">Notes</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            @foreach($transport->transportInventory as $transportInventory)
            <tr>
                <td>{{ $transportInventory->travelClass->name }}</td>
                <td>{{ $transportInventory->departs_at }}</td>
                <td>{{ $transportInventory->departure_time_confirmed ? "Yes" : "No" }}</td>
                <td>{{ $transportInventory->arrives_at }}</td>
                <td>{{ $transportInventory->arrival_time_confirmed ? "Yes" : "No" }}</td>
                <td>{{ $transportInventory->fit_selectable ? "Yes" : "No" }}</td>
                <td>{{ $transportInventory->stock }}</td>
                <td>{{ $transportInventory->purchase_price }}</td>
                <td>{{ $transportInventory->sales_price }}</td>
                <td>{{ $transportInventory->currency }}</td>
                <td>{{ $transportInventory->notes }}</td>
                <td>
                    <a href="{{route('transport-inventories.edit', ['transport' => $transport, 'transportInventory' => $transportInventory,])}}"
                        class="btn btn-sm btn-outline-success mb-1">
                        <i class="icon-note"></i>
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-danger mb-1"
                        onclick="event.preventDefault();document.getElementById('transportInventory-{{ $transportInventory->id }}-delete').submit();">
                        <i class="icon-trash"></i>
                    </a>
                    <form id="transportInventory-{{ $transportInventory->id }}-delete"
                        action="{{ route('transport-inventories.delete', ['transport' => $transport, 'transportInventory' => $transportInventory,]) }}"
                        method="POST" style="display: none;">{{ csrf_field() }}</form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
