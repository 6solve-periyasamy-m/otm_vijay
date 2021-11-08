<?php

use App\Http\Controllers\Api\DataTablesController;
use App\Http\Controllers\Api\SelectController;
use App\Http\Controllers\Api\TourComponentController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\TransportController;
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
Route::post('/booking/accommodation/{tour}/{order_customer}/{reference}/{accommodation_inventory}/{order}', [AccommodationController::class, 'postAccommodationBooking']);

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

Route::middleware('auth:api')->group(function() {
    
});

Route::middleware('api.token.auth')->name('api.')->group(function () {
    Route::prefix('select')->group(function () {
        Route::get('locations', [SelectController::class, 'getLocations'])->name('locations.select');
        Route::get('regions', [SelectController::class, 'getRegions'])->name('regions.select');
        Route::get('countries', [SelectController::class, 'getCountries'])->name('countries.select');
        Route::get('location-types', [SelectController::class, 'getLocationTypes'])->name('location-types.select');
        Route::get('room-types', [SelectController::class, 'getRoomTypes'])->name('room-types.select');
        Route::get('board-types', [SelectController::class, 'getBoardTypes'])->name('board-types.select');
        Route::get('transport-types', [SelectController::class, 'getTransportTypes'])->name('transport-types.select');
        Route::get('operators', [SelectController::class, 'getOperators'])->name('operators.select');
        Route::get('travel-classes', [SelectController::class, 'getTravelClasses'])->name('travel-classes.select');
        Route::get('activity-types', [SelectController::class, 'getActivityTypes'])->name('activity-types.select');
        Route::get('ticket-types', [SelectController::class, 'getTicketTypes'])->name('ticket-types.select');
        Route::get('events', [SelectController::class, 'getEvents'])->name('events.select');
        Route::get('tours', [SelectController::class, 'getTours'])->name('tours.select');
        Route::get('airports', [SelectController::class, 'getAirports'])->name('airports.select');
        Route::get('airlines', [SelectController::class, 'getAirlines'])->name('airlines.select');
        Route::get('quotes', [SelectController::class, 'getQuotes'])->name('quotes.select');
        Route::get('customer', [SelectController::class, 'getCustomers'])->name('customers.select');
        Route::get('hat-size', [SelectController::class, 'getHatSizes'])->name('hat-size.select');
        Route::get('t-shirt-size', [SelectController::class, 'getTShirtSizes'])->name('t-shirt-size.select');
        Route::get('payment-method', [SelectController::class, 'getPaymentMethods'])->name('payment-method.select');
        Route::prefix('inventory')->group(function () {
            Route::get('accommodation', [SelectController::class, 'getAccommodationInventory'])->name('inventory.accommodation.select');
            Route::get('activity', [SelectController::class, 'getActivityInventory'])->name('inventory.activity.select');
            Route::get('flight', [SelectController::class, 'getFlightInventory'])->name('inventory.flight.select');
            Route::get('transport', [SelectController::class, 'getTransportInventory'])->name('inventory.transport.select');
        });
        Route::prefix('selected')->group(function () {
            Route::get('location/{id}', [SelectController::class, 'getSelectedLocation'])->name('locations.selected');
            Route::get('region/{id}', [SelectController::class, 'getSelectedRegion'])->name('regions.selected');
            Route::get('country/{id}', [SelectController::class, 'getSelectedCountry'])->name('countries.selected');
            Route::get('location-type/{id}', [SelectController::class, 'getSelectedLocationType'])->name('location-types.selected');
            Route::get('room-type/{id}', [SelectController::class, 'getSelectedRoomType'])->name('room-types.selected');
            Route::get('board-type/{id}', [SelectController::class, 'getSelectedBoardType'])->name('board-types.selected');
            Route::get('transport-type/{id}', [SelectController::class, 'getSelectedTransportType'])->name('transport-types.selected');
            Route::get('operator/{id}', [SelectController::class, 'getSelectedOperator'])->name('operators.selected');
            Route::get('travel-class/{id}', [SelectController::class, 'getSelectedTravelClass'])->name('travel-classes.selected');
            Route::get('activity-type/{id}', [SelectController::class, 'getSelectedActivityType'])->name('activity-types.selected');
            Route::get('ticket-type/{id}', [SelectController::class, 'getSelectedTicketTypes'])->name('ticket-types.selected');
            Route::get('event/{id}', [SelectController::class, 'getSelectedEvent'])->name('events.selected');
            Route::get('tour/{id}', [SelectController::class, 'getSelectedTour'])->name('tours.selected');
            Route::get('airports/{id}', [SelectController::class, 'getSelectedAirport'])->name('airports.selected');
            Route::get('airlines/{id}', [SelectController::class, 'getSelectedAirline'])->name('airlines.selected');
            Route::get('quotes/{id}', [SelectController::class, 'getSelectedQuote'])->name('quotes.selected');
            Route::get('customers/{id}', [SelectController::class, 'getSelectedCustomer'])->name('customers.selected');
            Route::get('hat-size/{id}', [SelectController::class, 'getSelectedHatSize'])->name('hat-size.selected');
            Route::get('t-shirt-size/{id}', [SelectController::class, 'getSelectedTShirtSize'])->name('t-shirt-size.selected');
            Route::get('payment-method/{id}', [SelectController::class, 'getSelectedPaymentMethod'])->name('payment-method.selected');
            Route::prefix('inventory/{id}')->group(function () {
                Route::get('accommodation', [SelectController::class, 'getSelectedAccommodationInventory'])->name('inventory.accommodation.selected');
                Route::get('activity', [SelectController::class, 'getSelectedActivityInventory'])->name('inventory.activity.selected');
                Route::get('flight', [SelectController::class, 'getSelectedFlightInventory'])->name('inventory.flight.selected');
                Route::get('transport', [SelectController::class, 'getSelectedTransportInventory'])->name('inventory.transport.selected');
            });
        });
    });

    Route::prefix('datatables')->group(function () {
        Route::post('accommodation-inventory/{tour}', [DataTablesController::class, 'getAccommodationInventoryComponents'])->name('accommodation-inventory.datatables');
        Route::post('activity-inventory/{tour}', [DataTablesController::class, 'getActivityInventoryComponents'])->name('activity-inventory.datatables');
        Route::post('flight-inventory/{tour}', [DataTablesController::class, 'getFlightInventoryComponents'])->name('flight-inventory.datatables');
        Route::post('transport-inventory/{tour}', [DataTablesController::class, 'getTransportInventoryComponents'])->name('transport-inventory.datatables');
    });

    Route::prefix('component')->group(function() {
        Route::prefix('tour/{tour}')->group(function() {
            Route::prefix('accommodation/inventory')->group(function() {
                Route::post('/add', [AccommodationController::class, 'addAccommodationInventoryToTour'])->name('tour.accommodation.inventory.add');
            });
            Route::prefix('activity/inventory')->group(function() {
                Route::post('/add', [ActivityController::class, 'addActivityInventoryToTour'])->name('tour.activity.inventory.add');
            });
            Route::prefix('flight/inventory')->group(function() {
                Route::post('/add', [FlightController::class, 'addFlightInventoryToTour'])->name('tour.flight.inventory.add');
            });
            Route::prefix('transport/inventory')->group(function() {
                Route::post('/add', [TransportController::class, 'addTransportInventoryToTour'])->name('tour.transport.inventory.add');
            });
        });
    });

    Route::prefix('orders/addons')->name('addon')->group(function() {
        Route::post('/accommodation/add', [TourComponentController::class, 'addAccommodationAddon'])->name('accommodation.add');
        Route::post('/activity/add', [TourComponentController::class, 'addActivityAddon'])->name('activity.add');
        Route::post('/flight/add', [TourComponentController::class, 'addFlightAddon'])->name('flight.add');
        Route::post('/transport/add', [TourComponentController::class, 'addTransportAddon'])->name('transport.add');
    });
});
