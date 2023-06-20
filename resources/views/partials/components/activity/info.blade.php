@php/** @var \App\Models\Activity\Activity $activity */@endphp
<div class="otm-callout">
    <div class="row">
        @if(isset($activity->image_url))
            <div class="col-2">
                <img src="{{ asset($activity->image_url) }}" class="img-thumbnail image large">
            </div>
        @endif
        <div class="col-{{ isset($activity->image_url) ? 10 : 12 }} row">
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
                <p>Internal Notes</p>
                <h6 class="fw-bold">{{ $activity->internal_notes }}</h6>
            </div>
            @can('update', \App\Models\Activity\Activity::class)
            <div class="col-12">
                <a class="btn btn-success" href="{{ route('activities.edit', ['activity' => $activity, ]) }}">
                    {{ Icon::edit() }}
                    <span>Edit Activity</span>
                </a>
            </div>
            @endcan
        </div>
    </div>
</div>
