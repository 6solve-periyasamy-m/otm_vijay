<tr>
  <td><a href="{{ route('travelClasses.view', ['travelClass' => $travelClass,]) }}">{{ $title }}</a></td>
  <td>
    <a href="{{route('travel_classes.edit', ['travelClass' => $travelClass,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('travelClass-{{ $travelClass->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="travelClass-{{ $travelClass->id }}-delete" action="{{ route('travel_classes.delete', ['travelClass' => $travelClass,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
