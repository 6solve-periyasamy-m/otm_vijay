<tr>
  <td><a href="{{ route('roomTypes.view', ['roomType' => $roomType,]) }}">{{ $room_type_name }}</a></td>
  <td>{{ $maximum_occupancy }}</td>
  <td>
    <a href="{{route('room_types.edit', ['roomType' => $roomType,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('roomType-{{ $roomType->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="roomType-{{ $roomType->id }}-delete" action="{{ route('room_types.delete', ['roomType' => $roomType,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
