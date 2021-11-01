<tr>
    <td><a href="{{ route('flight-inventories.view', ['flightInventory' => $flightInventory,]) }}">{{ $flight_id }}</a>
    </td>
    <td>{{ $travel_class_id }}</td>
    <td>{{ $flight_number }}</td>
    <td>{{ $check_in }}</td>
    <td>{{ $departs_at }}</td>
    <td>{{ $arrives_at }}</td>
    <td>{{ $fit_selectable }}</td>
    <td>{{ $stock }}</td>
    <td>{{ $purchase_price }}</td>
    <td>{{ $sales_price }}</td>
    <td>{{ $currency }}</td>
    <td>{{ $notes }}</td>
    <td>
        <a href="{{route('flight-inventories.edit', ['flightInventory' => $flightInventory,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('flightInventory-{{ $flightInventory->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="flightInventory-{{ $flightInventory->id }}-delete"
              action="{{ route('flight-inventories.delete', ['flightInventory' => $flightInventory,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
