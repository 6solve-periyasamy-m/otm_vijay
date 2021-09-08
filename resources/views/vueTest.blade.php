@extends ('layout.main')
@section('content')
<div class="container-fluid" id="app">
    Vue and API tests <a href="/">HOME</a>
    <vue-test></vue-test>
    <atol-certificate
        travellers="Traveller1, Traveller2, Traveller3 and Traveller4"
        tour="The Tour Details"
        flightOutward="Flight Outward Details"
        flightInward="Flight Inward Details"
        ATOL="ATOL123123123123"
        OTM="Octopus Travel Matrix Company"
        msg="Customised ATOL Certificate Generator"
    ></atol-certificate>
    <div>
        <h1>API Tests</h1>
        <ul>
            <li>
                <a href="/api/booking/accommodation/1" target="test">
                    Route::get('/booking/accommodation{tour}', [AccommodationController::class, 'getAccommodationInventoryForTour']);
                </a>
            </li>
            <li>
                <a href="/api/booking/accommodation/1" target="test">
                    Route::get('/booking/accommodation/customer/{tour}/{order}/{token}', [AccommodationController::class, 'getAccommodationBooking']);
                </a>
            </li>
            <li>
                <a href="/api/booking/flight/orders/1" target="test">
                    Route::get('/booking/flight/orders/{order_id}', [FlightController::class, 'loadFlightsForOrder']);
                </a>
            </li>
            <li>
                <a href="/api/booking/flights/1/outbound" target="test">
                    Route::get('/booking/flights/{tour_id}/{flight_type}', [FlightController::class, 'getFlightInventoriesForTour']);
                </a>
            </li>
            <li>
                <a href="/api/booking/flights/1" target="test">
                    Route::get('/booking/flights/{tour_id}', [FlightController::class, 'getFlightInventoriesForTour']);
                </a>
            </li>
            <li>
                <a href="/api/booking/flight-inventories" target="test">
                    Route::get('/booking/flight-inventories', [FlightController::class, 'getFlightsInventories']);
                </a>
            </li>
            <li>
                <a href="/api/booking/flights/airport/1" target="test">
                    Route::get('/booking/flights/airport/{airport}', [FlightController::class, 'getFlightsFromAirport']);
                </a>
            </li>
        </ul>
    </div>
    <iframe height="200px" width="100%" name="test">
    </iframe>

</div>
@endsection
