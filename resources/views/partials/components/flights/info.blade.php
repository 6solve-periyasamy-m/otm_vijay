<style>
    .info-col {
        width: 25%;
        text-align: center;
        border: 1px solid black;
    }
</style>

<div class="otm-callout">
    <h3 class="fw-bold">{{ $flight->airline->name }}</h3>
    <p class="fw-bold">{{ $flight->arrivalAirport->name }}</p>
    <p class="fw-bold">{{ $flight->departureAirport->name }}</p>
    <p class="fw-bold">{{ $flight->is_domestic ? "Domestic" : "International" }}</p>
    <p>{{ $flight->notes }} </p><br>    
    <a class="btn btn-success" href="{{route('flights.edit', ['flight' => $flight,])}}">
        <i class="icon-note"></i>
        <span>Edit Accommodation</span>
    </a>
</div>
