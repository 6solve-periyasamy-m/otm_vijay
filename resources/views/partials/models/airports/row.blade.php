<tr>
  <td><a href="{{ route('airports.view', ['airport' => $airport,]) }}">{{ $name }}</a></td>
  <td>{{ $iata_code }}</td>
  <td>
    <a href="{{route('airports.edit', ['airport' => $airport,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('airport-{{ $airport->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="airport-{{ $airport->id }}-delete" action="{{ route('airports.delete', ['airport' => $airport,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
