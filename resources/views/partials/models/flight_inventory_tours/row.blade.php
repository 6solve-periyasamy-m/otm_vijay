<tr>
    <td>
        <a href="{{ route('flight-inventory-tours.view', ['flightInventoryTour' => $flightInventoryTour,]) }}">{{ $tour_id }}</a>
    </td>
    <td>{{ $flight_inventory_id }}</td>
    <td>{{ $tour_component_type }}</td>
    <td>{{ $flight_type }}</td>
    <td>{{ $tour_sales_price }}</td>
    <td>
        <a href="{{route('flight-inventory-tours.edit', ['flightInventoryTour' => $flightInventoryTour,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('flightInventoryTour-{{ $flightInventoryTour->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="flightInventoryTour-{{ $flightInventoryTour->id }}-delete"
              action="{{ route('flight-inventory-tours.delete', ['flightInventoryTour' => $flightInventoryTour,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
