<tr>
    <td><a href="{{ route('room-types.view', ['roomType' => $roomType,]) }}">{{ $name }}</a></td>
    <td>{{ $maximum_occupancy }}</td>
    <td>
        <a href="{{route('room-types.edit', ['roomType' => $roomType,])}}">
            <ion-icon name="create"></ion-icon>
        </a>
        <a href="#"
           onclick="event.preventDefault();document.getElementById('roomType-{{ $roomType->id }}-delete').submit();">
            <ion-icon name="trash"></ion-icon>
        </a>
        <form id="roomType-{{ $roomType->id }}-delete"
              action="{{ route('room-types.delete', ['roomType' => $roomType,]) }}" method="POST"
              style="display: none;">{{ csrf_field() }}</form>
    </td>
</tr>
