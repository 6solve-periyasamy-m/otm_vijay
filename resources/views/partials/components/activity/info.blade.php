<div class="otm-callout">
    <h4 class="fw-bold">{{ $activity->name }}</h4>
    <p class="fw-bold">{{ $activity->description }}</p>
    <p class="fw-bold">{{ $activity->location->name }}</p>
    <p> {{ $activity->notes }} </p>
    <br>
    <a class="btn btn-success" href="{{ route('activities.edit', ['activity' => $activity, ]) }}">
        <i class="icon-note"></i>
        <span>Edit Activity</span>
    </a>
</div>
