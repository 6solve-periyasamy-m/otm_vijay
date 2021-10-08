<table style="width: 100%;">
    <tr>
        <td style="width: 20%; text-align: center; border: 1px solid black"><h2>{{ $activity->title }}</h2></td>
        <td style="width: 20%; text-align: center; border: 1px solid black"><h2>{{ $activity->description }}</h2></td>
        <td style="width: 60%; text-align: center; border: 1px solid black"><h2>{{ $activity->location->name }}</h2></td>
    </tr>
</table>
{{ $activity->notes }}
<a class="btn btn-success" href="{{ route('activities.edit', ['activity' => $activity, ]) }}">Edit Activity</a>
