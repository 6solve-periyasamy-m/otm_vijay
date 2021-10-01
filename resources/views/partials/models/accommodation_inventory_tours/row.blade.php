<tr>
    <td>
        <a href="{{ route('accommodation-inventory-tours.view', ['accommodationInventoryTour' => $accommodationInventoryTour,]) }}">{{ $tour_id }}</a>
    </td>
    <td>{{ $accommodation_inventory_id }}</td>
    <td>{{ $tour_component_type }}</td>
    <td>{{ $tour_sales_price }}</td>
    <td>
        <a href="{{route('accommodation-inventory-tours.edit', ['accommodationInventoryTour' => $accommodationInventoryTour,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('accommodationInventoryTour-{{ $accommodationInventoryTour->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="accommodationInventoryTour-{{ $accommodationInventoryTour->id }}-delete"
              action="{{ route('accommodation-inventory-tours.delete', ['accommodationInventoryTour' => $accommodationInventoryTour,]) }}"
              method="POST" style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
