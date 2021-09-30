<tr>
  <td><a href="{{ route('locationTypes.view', ['locationType' => $locationType,]) }}">{{ $name }}</a></td>
  <td>
    <a href="{{route('location_types.edit', ['locationType' => $locationType,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('locationType-{{ $locationType->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="locationType-{{ $locationType->id }}-delete" action="{{ route('location_types.delete', ['locationType' => $locationType,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
