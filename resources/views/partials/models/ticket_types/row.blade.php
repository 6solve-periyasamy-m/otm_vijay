<tr>
  <td><a href="{{ route('ticketTypes.view', ['ticketType' => $ticketType,]) }}">{{ $name }}</a></td>
  <td>
    <a href="{{route('ticket_types.edit', ['ticketType' => $ticketType,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('ticketType-{{ $ticketType->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="ticketType-{{ $ticketType->id }}-delete" action="{{ route('ticket_types.delete', ['ticketType' => $ticketType,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
