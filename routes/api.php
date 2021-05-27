<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\AirlinesController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\AccommodationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* valid public routes */
Route::get('/booking/events',           [ApiController::class, 'getEvents']);
Route::get('/booking/tours/{event_id}', [ApiController::class, 'getTours']);
Route::get('/booking/tour/{id}',        [ApiController::class, 'getBasicTourInformation']);

Route::get('/booking/airlines',         [AirlinesController::class, 'getAirlines']);
Route::get('/booking/airports',         [AirlinesController::class, 'getAirports']);

// Travellers
Route::get('/booking/tourparty', [CustomerController::class, 'getTravellers']);
Route::get('/booking/customer/{token}', [CustomerController::class, 'getCustomerOrderByToken']);

// Flights
Route::get('/booking/flight/orders/{order_id}', [FlightController::class, 'loadFlightsForOrder']);
Route::get('/booking/flights/{tour_id}/{flight_type}', [FlightController::class, 'getFlightInventoriesForTour']);
Route::get('/booking/flights/{tour_id}', [FlightController::class, 'getFlightInventoriesForTour']);
Route::get('/booking/flight-inventories', [FlightController::class, 'getFlightsInventories']);
Route::get('/booking/flights/airport/{airport}', [FlightController::class, 'getFlightsFromAirport']);

// Accommodation
Route::get('/booking/accommodation/{tour}', [AccommodationController::class, 'getAccommodationInventoryForTour']);
Route::get('/booking/accommodation/customer/{tour}/{order}/{token}', [AccommodationController::class, 'getAccommodationBooking']);
Route::post('/booking/accommodation/{tour}/{orders_customer}/{reference}/{accommodation_inventory}/{order}', [AccommodationController::class, 'postAccommodationBooking']);

// POST routes (requires AUTH)
// store travellers
Route::post('/booking/lead-traveller', [BookingController::class, 'leadTraveller']);
Route::post('/booking/additional-traveller', [BookingController::class, 'additionalTraveller']);
// create Booking Order
Route::post('/booking/create-order', [BookingController::class, 'createOrder']);
// create Flights Order
Route::post('/booking/flight/{customer}/{tour}/{order}/{flight_type}/{flight}/{custom}/{reference}', [
    BookingController::class, 'bookFlightDetails'
]);




Route::get('/booking/accomodation', [ApiController::class, 'getAccommodationFromTour']);
Route::get('/booking/payment-schedules', [ApiController::class, 'getPaymentSchedules']);
Route::get('/booking/payment-schedule/{id}', [ApiController::class, 'getPaymentSchedule']);

Route::middleware('auth:api')->group(function() {
    
});
