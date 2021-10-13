@extends ('layout.main')
@section('content')
<div class="container-fluid page-testing" id="app">
    <h5>Octopus Travel Matrix</h5>
    <h1>Frontend Tests</h1>
    <a href="/">HOME</a>
    <atol-certificate
        travellers="Traveller1, Traveller2, Traveller3 and Traveller4"
        passengers="4"
        tour="The Tour Details"
        flight-outward="Flight Outward Details"
        flight-inward="Flight Inward Details"
        atol="ATOL123123123123"
        issuer-long="Octopus Travel Matrix Company"
        issuer="Octopus Travel Matrix"
        msg="Customised ATOL Certificate Generator"
    ></atol-certificate>
    <div class="container">
        <div class="panel">
            <h1>VueJS Components</h1>
            <vue-test></vue-test>
            <booking-info></booking-info>
            <div class="panel">
            <h1>API Tests</h1>
            <ul>
                <li>
                    <a href="/api/booking/accommodation/settings" target="test">
                        Route::get('/booking/accommodation/settings', [AccommodationController::class, 'getAccommodationSettings']);
                    </a>
                </li>
                <li>
                    <a href="/api/booking/accommodation/options/1" target="test">
                        Route::get('/booking/accommodation/options/{tour}', [AccommodationController::class, 'getAccommodationOptions']);
                    </a>
                </li>
                <li>
                    <a href="/api/booking/accommodation/tour/1" target="test">
                        Route::get('/booking/accommodation/tour/{tour}', [AccommodationController::class, 'getAccommodationInventoryForTour']);
                    </a>
                </li>
                <li>
                    <a href="/api/booking/accommodation/customer/1/1/abcdefghijklmn/1,2,3,4,5" target="test">
                        Route::get('/booking/accommodation/customer/{tour}/{order}/{token}/{travellers}', [AccommodationController::class, 'getAccommodationBooking']);
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
                <li>
                    <a target="test" href="/booking/check/tour/1">Check Tour for event 1</a>
                </li>
                <li>
                    <a target="test" href="/booking/check/events">Check events</a>
                </li>
            </ul>
            <h3>Test output</h3>
            <iframe height="200px" width="100%" name="test">
            </iframe>
        </div>
        </div>
    </div>
</div>
@endsection
