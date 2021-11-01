<tr>
    <td>
        <a href="{{ route('accommodation-inventories.view', ['accommodationInventory' => $accommodationInventory,]) }}">{{ $accommodation_id }}</a>
    </td>
    <td>{{ $room_type_id }}</td>
    <td>{{ $board_type_id }}</td>
    <td>{{ $check_in }}</td>
    <td>{{ $checked_in }}</td>
    <td>{{ $check_out }}</td>
    <td>{{ $checked_out }}</td>
    <td>{{ $fit_selectable }}</td>
    <td>{{ $stock }}</td>
    <td>{{ $purchase_price }}</td>
    <td>{{ $sales_price }}</td>
    <td>{{ $notes }}</td>
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
