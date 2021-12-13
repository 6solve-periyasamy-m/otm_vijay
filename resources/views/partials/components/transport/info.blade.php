<div class="otm-callout">
    <div class="row">
        <div class="col-12 text-capitalize">
            <h4 class="fw-bold">{{ $transport->name }}</h4>
        </div>
        <div class="col-12 col-xl-6">
            <p>Transport Type</p>
            <h6 class="fw-bold">{{ $transport->transportType->name }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Operator</p>
            <h6 class="fw-bold">{{ $transport->operator->name }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Departure Location</p>
            <h6 class="fw-bold">{{ $transport->departureAddress}}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Arrival Location</p>
            <h6 class="fw-bold">{{ $transport->arrivalAddress }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Currency</p>
            <h6 class="fw-bold">{{ $transport->currency }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Description</p>
            <h6 class="fw-bold">{{ $transport->description }}</h6>
        </div>
        @can('update', \App\Models\Transport::class)
        <div class="col-12">
            <a class="btn btn-success" href="{{route('transports.edit', ['transport' => $transport,])}}">
                <i class="icon-note"></i>
                <span>Edit Transport</span>
            </a>
        </div>
        @endcan
    </div>
</div>
