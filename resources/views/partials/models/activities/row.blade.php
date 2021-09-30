<tr>
  <td><a href="{{ route('activities.view', ['activity' => $activity,]) }}">{{ $activity_type_id }}</a></td>
  <td>{{ $location_id }}</td>
  <td>{{ $title }}</td>
  <td>{{ $description }}</td>
  <td>{{ $notes }}</td>
  <td>
    <a href="{{route('activities.edit', ['activity' => $activity,])}}"><ion-icon name="create"></ion-icon></a>
    <a href="#" onclick="event.preventDefault();document.getElementById('activity-{{ $activity->id }}-delete').submit();"><ion-icon name="trash"></ion-icon></a>
    <form id="activity-{{ $activity->id }}-delete" action="{{ route('activities.delete', ['activity' => $activity,]) }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
  </td>
</tr>
