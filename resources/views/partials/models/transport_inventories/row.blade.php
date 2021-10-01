<tr>
  <td><a href="{{ route('transport-inventories.view', ['transportInventory' => $transportInventory,]) }}">{{ $transport_id }}</a></td>
  <td>{{ $travel_class_id }}</td>
  <td>{{ $departure_date_time }}</td>
  <td>{{ $departure_confirmed }}</td>
  <td>{{ $arrival_date_time }}</td>
  <td>{{ $arrival_confirmed }}</td>
  <td>{{ $fit_selectable }}</td>
  <td>{{ $stock }}</td>
  <td>{{ $purchase_price }}</td>
  <td>{{ $sales_price }}</td>
  <td>{{ $currency }}</td>
  <td>{{ $notes }}</td>
  <td>
    <a href="{{route('transport-inventories.edit', ['transportInventory' => $transportInventory,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('transportInventory-{{ $transportInventory->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="transportInventory-{{ $transportInventory->id }}-delete" action="{{ route('transport-inventories.delete', ['transportInventory' => $transportInventory,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
