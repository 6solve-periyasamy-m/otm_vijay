<div class="otm-callout">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold">{{ $activity->name }}</h4>
        </div>       
        <div class="col-12 col-xl-6">
            <p>Location</p>
            <h6 class="fw-bold">{{ $activity->address }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Description</p>
            <h6 class="fw-bold">{{ $activity->description }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Currency</p>
            <h6 class="fw-bold">{{ $activity->currency }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Notes</p>
            <h6 class="fw-bold">{{ $activity->notes }}</h6>
        </div>
        @can('update', \App\Models\Activity::class)
        <div class="col-12">
            <a class="btn btn-success" href="{{ route('activities.edit', ['activity' => $activity, ]) }}">
                <i class="icon-note"></i>
                <span>Edit Activity</span>
            </a>
        </div>
        @endcan
    </div>
</div>
