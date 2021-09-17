<?php

use App\Http\Controllers\Api\TourComponentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\AirlinesController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\AccommodationController;
use App\Http\Controllers\Api\PaymentController;

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
Route::get('/booking/events',           [TourController::class, 'getEvents']);
Route::get('/booking/tours/{event_id}', [TourController::class, 'getTours']);
Route::get('/booking/tour/{id}',        [TourController::class, 'getBasicTourInformation']);

Route::get('/booking/airlines',         [AirlinesController::class, 'getAirlines']);
Route::get('/booking/airports',         [AirlinesController::class, 'getAirports']);

Route::get('/booking/findOrderByEmail/{email}', [CustomerController::class, 'getCustomerOrdersByEmail']);
// Travellers
Route::get('/booking/tourparty', [CustomerController::class, 'getTravellers']);
Route::get('/booking/customer/{token}', [CustomerController::class, 'getCustomerOrderByToken']);

// Flights
Route::get('/booking/flight/orders/{order_id}', [FlightController::class, 'loadFlightsForOrder']);
Route::get('/booking/flights/{tour_id}/{flight_type}', [FlightController::class, 'getFlightInventoriesForTour']);
Route::get('/booking/flights/{tour_id}', [FlightController::class, 'getFlightInventoriesForTour']);
Route::get('/booking/flight-inventories', [FlightController::class, 'getFlightsInventories']);
Route::get('/booking/flights/airport/{airport}', [FlightController::class, 'getFlightsFromAirport']);
// function removeCustomerOrderDetail($componentType, $order, $orderCustomer, $type, $custom, $reference)
// Route::post('/booking/flights/remove/flight/{order_id}/{order_customer_id}/{component_type}/{custom}/{inventory_tour_id}', [BookingController::class, 'removeFlightBooking']);
Route::post('/booking/flights/remove/flight', [BookingController::class, 'removeFlightBooking']);

// Accommodation
Route::get('/booking/accommodation/customer/{tour}/{order}/{token}', [AccommodationController::class, 'getAccommodationBooking']);
Route::get('/booking/accommodation/{tour}', [AccommodationController::class, 'getAccommodationInventoryForTour']);
Route::post('/booking/accommodation/reserve', [AccommodationController::class, 'postAccommodationReservation']);
Route::post('/booking/accommodation/{tour}/{orders_customer}/{reference}/{accommodation_inventory}/{order}', [AccommodationController::class, 'postAccommodationBooking']);

// accommodation rooms
Route::get('/accommodation/rooms/tour/{tour}/{order_id}', [AccommodationController::class, 'loadRoomsForTour']);
// POST routes (requires AUTH)

// store travellers
Route::post('/booking/lead-traveller', [BookingController::class, 'leadTraveller']);
Route::post('/booking/additional-traveller', [BookingController::class, 'additionalTraveller']);
Route::post('/booking/additional-traveller/remove', [BookingController::class, 'removeAdditionalTraveller']);

// create Booking Order
Route::post('/booking/create-order', [BookingController::class, 'createOrder']);

// create Flights Order
// Route::post('/booking/flight/{customer}/{tour}/{order}/{flight_type}/{flight}/{custom}/{reference}', [
//     BookingController::class, 'bookFlightDetails'
// ]);
Route::post('/booking/flight', [BookingController::class, 'bookFlightDetails']);

Route::get('/booking/accomodation', [ApiController::class, 'getAccommodationFromTour']);
Route::get('/booking/payment-schedules', [PaymentController::class, 'getPaymentSchedules']);
Route::get('/booking/payment-schedule/{id}', [PaymentController::class, 'getPaymentSchedule']);

Route::get('/orders/accommodation/{oCustomerId}/available', [TourComponentController::class, 'getAvailableAccommodationAddons'])->name('getAvailableAccommodationAddons');
Route::get('/orders/activities/{oCustomerId}/available', [TourComponentController::class, 'getAvailableActivityAddons'])->name('getAvailableActivityAddons');
Route::get('/orders/flights/{oCustomerId}/available', [TourComponentController::class, 'getAvailableFlightAddons'])->name('getAvailableFlightAddons');
Route::get('/orders/transports/{oCustomerId}/available', [TourComponentController::class, 'getAvailableTransportAddons'])->name('getAvailableTransportAddons');

Route::post('/orders/accommodation/add', [TourComponentController::class, 'addAccommodationAddon'])->name('addAccommodationAddon');
Route::post('/orders/activity/add', [TourComponentController::class, 'addActivityAddon'])->name('addActivityAddon');
Route::post('/orders/flight/add', [TourComponentController::class, 'addFlightAddon'])->name('addFlightAddon');

Route::middleware('auth:api')->group(function() {
    
});
