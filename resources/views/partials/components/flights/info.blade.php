<div class="otm-callout">
    <div class="row">
        <div class="col-12">
            <h4 class="fw-bold">{{ $flight->airline->name }}</h4>
        </div>
        <div class="col-12 col-xl-6">
            <p>Departure Airport</p>
            <h6 class="fw-bold">{{ $flight->departureAirport->address }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Arrival Airport</p>
            <h6 class="fw-bold">{{ $flight->arrivalAirport->address }}</h6>
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
            <p>Notes</p>
            <h6 class="fw-bold">{{ $flight->notes }}</h6>
        </div>
        @can('update', \App\Models\Flight::class)
        <div class="col-12">
            <a class="btn btn-success" href="{{route('flights.edit', ['flight' => $flight,])}}">
                <i class="icon-note"></i>
                <span>Edit Flight</span>
            </a>
        </div>
        @endcan
    </div>
</div>
