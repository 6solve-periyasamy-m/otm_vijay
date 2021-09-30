<tr>
  <td><a href="{{ route('activityTypes.view', ['activityType' => $activityType,]) }}">{{ $name }}</a></td>
  <td>
    <a href="{{route('activity_types.edit', ['activityType' => $activityType,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('activityType-{{ $activityType->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="activityType-{{ $activityType->id }}-delete" action="{{ route('activity_types.delete', ['activityType' => $activityType,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
