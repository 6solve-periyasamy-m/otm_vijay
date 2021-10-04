<a href="{{ route('accommodation-inventories.create') }}" class="btn btn-primary">Add Inventory</a>
<a href="#" class="btn btn-success">Bulk Add Inventory</a>
<table id="accommodationInventory" style="width: 100%;" class="table table-striped">
    <thead class="thead-dark">
    <tr>
        <th scope="col">Room Type</th>
        <th scope="col">Board Type</th>
        <th scope="col">Check In Date Time</th>
        <th scope="col">Checkin Confirmed</th>
        <th scope="col">Check Out Date Time</th>
        <th scope="col">Checkout Confirmed</th>
        <th scope="col">Fit Selectable</th>
        <th scope="col">Stock</th>
        <th scope="col">Purchase Price</th>
        <th scope="col">Sales Price</th>
        <th scope="col">Notes</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    @foreach($accommodation->inventory as $accommodationInventory)
        <tr>
            <td>{{ $accommodationInventory->roomType->room_type_name }}</td>
            <td>{{ $accommodationInventory->boardType->board_type_name }}</td>
            <td>{{ $accommodationInventory->check_in_date_time }}</td>
            <td>{{ $accommodationInventory->checkin_confirmed }}</td>
            <td>{{ $accommodationInventory->check_out_date_time }}</td>
            <td>{{ $accommodationInventory->checkout_confirmed }}</td>
            <td>{{ $accommodationInventory->fit_selectable }}</td>
            <td>{{ $accommodationInventory->stock }}</td>
            <td>{{ $accommodationInventory->purchase_price }}</td>
            <td>{{ $accommodationInventory->sales_price }}</td>
            <td>{{ $accommodationInventory->notes }}</td>
            <td>
                <a href="{{route('accommodation-inventories.edit', ['accommodationInventory' => $accommodationInventory,])}}">
                    <ion-icon name="create"></ion-icon>
                </a>
                <a href="#"
                   onclick="event.preventDefault();document.getElementById('accommodationInventory-{{ $accommodationInventory->id }}-delete').submit();">
                    <ion-icon name="trash"></ion-icon>
                </a>
                <form id="accommodationInventory-{{ $accommodationInventory->id }}-delete"
                      action="{{ route('accommodation-inventories.delete', ['accommodationInventory' => $accommodationInventory,]) }}"
                      method="POST" style="display: none;">{{ csrf_field() }}</form>
            </td>
        </tr>
    @endforeach
</table>
