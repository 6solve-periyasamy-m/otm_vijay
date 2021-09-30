<tr>
  <td><a href="{{ route('transportInventoryTours.view', ['transportInventoryTour' => $transportInventoryTour,]) }}">{{ $tour_id }}</a></td>
  <td>{{ $transport_inventory_id }}</td>
  <td>
    <a href="{{route('transport_inventory_tours.edit', ['transportInventoryTour' => $transportInventoryTour,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('transportInventoryTour-{{ $transportInventoryTour->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="transportInventoryTour-{{ $transportInventoryTour->id }}-delete" action="{{ route('transport_inventory_tours.delete', ['transportInventoryTour' => $transportInventoryTour,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
