@section('footer-script')
<script type="text/javascript">
    $(document).ready(function () { $('#accommodationInventory').DataTable({fixedHeader: true}); });
</script>
@endsection
<div class="card">
    <div class="card-body ">
        <a href="#" class="btn btn-success float-end">Bulk Add Inventory</a>
        <a href="{{ route('accommodation-inventories.create', ['accommodation' => $accommodation, ]) }}" class="btn btn-primary float-end me-1">
            <i class="icon-plus"></i>
            <span>Add Inventory</span>
        </a>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <table id="accommodationInventory" style="width: 100%;" class="table table-striped">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Room Type</th>
                <th scope="col">Board Type</th>
                <th scope="col">Check In Time</th>
                <th scope="col">Confirmed</th>
                <th scope="col">Check Out Time</th>
                <th scope="col">Confirmed</th>
                <th scope="col">Fit Selectable</th>
                <th scope="col">Stock</th>
                <th scope="col">Purchase Price</th>
                <th scope="col">Sales Price</th>
                <th scope="col">Currency</th>
                <th scope="col">Notes</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            @foreach($accommodation->inventory as $accommodationInventory)
                <tr>
                    <td>{{ $accommodationInventory->roomType->name }}</td>
                    <td>{{ $accommodationInventory->boardType->name }}</td>
                    <td>{{ $accommodationInventory->check_in }}</td>
                    <td>{{ $accommodationInventory->check_in_time_confirmed == 1 ? 'True' : 'False' }}</td>
                    <td>{{ $accommodationInventory->check_out }}</td>
                    <td>{{ $accommodationInventory->check_out_time_confirmed == 1 ? 'True' : 'False' }}</td>
                    <td>{{ $accommodationInventory->fit_selectable == 1 ? 'True' : 'False' }}</td>
                    <td>{{ $accommodationInventory->stock }}</td>
                    <td>{{ $accommodationInventory->purchase_price }}</td>
                    <td>{{ $accommodationInventory->sales_price }}</td>
                    <td>{{ $accommodationInventory->currency }}</td>
                    <td>{{ $accommodationInventory->notes }}</td>
                    <td class="actions">
                        <a href="{{route('accommodation-inventories.edit', ['accommodation' => $accommodation, 'accommodationInventory' => $accommodationInventory,])}}" 
                            class="btn btn-outline-success btn-sm mb-1">
                            <i class="icon-note"></i>
                        </a>
                        <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                        onclick="event.preventDefault();document.getElementById('accommodationInventory-{{ $accommodationInventory->id }}-delete').submit();">
                            <i class="icon-trash"></i>
                        </a>
                        <form id="accommodationInventory-{{ $accommodationInventory->id }}-delete"
                            action="{{ route('accommodation-inventories.delete', ['accommodation' => $accommodation, 'accommodationInventory' => $accommodationInventory,]) }}"
                            method="POST" style="display: none;">{{ csrf_field() }}</form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
