<div class="otm-callout">
    <div class="row">
        <div class="col-12">
            <h3 class="fw-bold">{{ $accommodation->name }}</h3>
        </div>
        <div class="col-12 col-xl-6">
            <p>Audit Date</p>
            <h6 class="fw-bold">{{ $accommodation->audit_date }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Address</p>
            <h6 class="fw-bold">{{ $accommodation->address }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>description</p>
            <h6 class="fw-bold">{{ $accommodation->description }}</h6>
        </div>
        <div class="col-12">
            <a class="btn btn-success" href="{{route('accommodations.edit', ['accommodation' => $accommodation,])}}">
                <i class="icon-note"></i>
                <span>Edit Accommodation</span>
            </a>
        </div>
    </div>
</div>
