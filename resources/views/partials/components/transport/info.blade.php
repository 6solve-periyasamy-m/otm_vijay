@php/** @var \App\Models\Transport\Transport $transport */@endphp
<div class="otm-callout">
    <div class="row">
        @if(isset($transport->image_url))
            <div class="col-2">
                <img src="{{ asset($transport->image_url) }}" class="img-thumbnail image large">
            </div>
        @endif
        <div class="col-{{ isset($transport->image_url) ? 10 : 12 }} row">
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
            <div class="col-12 col-xl-6">
                <p>Internal Notes</p>
                <h6 class="fw-bold">{{ $transport->internal_notes }}</h6>
            </div>
            @can('update', \App\Models\Transport\Transport::class)
            <div class="col-12">
                @can('update', \App\Models\Transport\Transport::class)
                <a class="btn btn-success" href="{{route('transports.edit', ['transport' => $transport,])}}">
                    {{ Icon::edit() }}
                    <span>Edit Transport</span>
                </a>
                @endcan
                <a class="btn btn-secondary" href="{{route('transports.manifest.view', ['transport' => $transport,])}}">
                    {{ Icon::list() }}
                    <span>View Manifest</span>
                </a>
            </div>
        </div>
    </div>
</div>
