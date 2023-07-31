@php
    /** @var \App\Models\Flight\Flight $flight */
@endphp
<div class="otm-callout">
    <div class="row">
        @if(isset($flight->image_url))
            <div class="col-2">
                <img src="{{ asset($flight->image_url) }}" class="img-thumbnail image large">
            </div>
        @endif
        <div class="col-{{ isset($flight->image_url) ? 10 : 12 }} row">
            <div class="col-12">
                <h4 class="fw-bold">{{ $flight->airline->name }}</h4>
            </div>
            <div class="col-12 col-xl-6">
                <p>Departure Airport</p>
                <h6 class="fw-bold">{{ $flight->departureAirport }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Arrival Airport</p>
                <h6 class="fw-bold">{{ $flight->arrivalAirport }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Is Domestic</p>
                <h6 class="fw-bold">{{ $flight->is_domestic ? "Domestic" : "International" }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Currency</p>
                <h6 class="fw-bold">{{ $flight->currency }}</h6>
            </div>
            <div class="col-12 col-xl-6">
                <p>Internal Notes</p>
                <h6 class="fw-bold">{{ $flight->internal_notes }}</h6>
            </div>

            <div class="col-12">
                @can('update', \App\Models\Flight\Flight::class)
                <a class="btn btn-success" href="{{route('flights.edit', ['flight' => $flight,])}}">
                    {{ Icon::edit() }}
                    <span>Edit Flight</span>
                </a>
                @endcan
                <a class="btn btn-secondary" href="{{route('flights.manifest.view', ['flight' => $flight,])}}">
                    {{ Icon::list() }}
                    <span>View Manifest</span>
                </a>
            </div>
        </div>
    </div>
</div>
