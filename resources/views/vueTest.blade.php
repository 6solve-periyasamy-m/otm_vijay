@extends ('layout.main')
@section('content')
<div class="container-fluid" id="app">
    Vue and API tests <a href="/">HOME</a>
    <vue-test></vue-test>
    <div>
        <h1>API Tests</h1>
        <ul>
            <!--
// Accommodation
Route::get('/booking/accommodation/customer/{tour}/{order}/{token}', [AccommodationController::class, 'getAccommodationBooking']);
Route::get('/booking/accommodation/{tour}', [AccommodationController::class, 'getAccommodationInventoryForTour']);
            -->
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
