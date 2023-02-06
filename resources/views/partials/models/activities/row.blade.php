<tr>
    <td><a href="{{ route('activities.view', ['activity' => $activity,]) }}">{{ $name }}</a></td>
    <td>{{ $activity->activityType->name }}</td>
    <td>{{ $activity->address }}</td>
    <td>{{ $description }}</td>
    <td>{{ $notes }}</td>
    <td class="actions">
        @can('update', \App\Models\Activity\Activity::class)
            <a href="{{route('activities.edit', ['activity' => $activity,])}}" class="btn btn-sm btn-outline-success mb-1">
                <x-icon icon="note" />
            </a>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            <x-icon icon="note" />
        </span>
        @endcan
        @can('delete', \App\Models\Activity\Activity::class)
            <a href="#" class="btn btn-sm btn-outline-danger mb-1"
               onclick="event.preventDefault();document.getElementById('activity-{{ $activity->id }}-delete').submit();">
                <x-icon icon="trash" />
            </a>
            <form id="activity-{{ $activity->id }}-delete"
                  action="{{ route('activities.delete', ['activity' => $activity,]) }}" method="POST"
                  style="display: none;">{{ csrf_field() }}</form>
        @else
            <span class="btn btn-outline-dark btn-sm mb-1">
            <x-icon icon="trash" />
        </span>
        @endcan
    </td>
</tr>
