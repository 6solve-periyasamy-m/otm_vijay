<div class="otm-callout">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold">{{ $accommodation->name }}</h4>
        </div>
        <div class="col-12 col-xl-6">
            <p>Audit Date</p>
            <h6 class="fw-bold">{{ StringFormatter::formatDate($accommodation->audit_date) }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Address</p>
            <h6 class="fw-bold">{{ $accommodation->address }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Description</p>
            <h6 class="fw-bold">{{ $accommodation->description }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Currency</p>
            <h6 class="fw-bold">{{ $accommodation->currency }}</h6>
        </div>
        @can('update', \App\Models\Accommodation::class)
        <div class="col-12">
            <a class="btn btn-success" href="{{route('accommodations.edit', ['accommodation' => $accommodation,])}}">
                <i class="icon-note"></i>
                <span>Edit Accommodation</span>
            </a>
        </div>
        @endcan
    </div>
</div>
