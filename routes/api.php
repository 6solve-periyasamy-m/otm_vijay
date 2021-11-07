<?php
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
use App\Http\Controllers\Api\BookingCustomerController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\AccommodationController;
use App\Http\Controllers\Api\PaymentController;

/**
 * Booking form routes are PUBLIC (do not use api auth)
 */
// Booking
Route::get('/booking/token/{token}', [BookingController::class, 'get']);
// Accommodation
Route::get('/booking/accommodation/settings', [AccommodationController::class, 'getAccommodationSettings']);
Route::get('/booking/accommodation/options/{tour}', [AccommodationController::class, 'getAccommodationOptions']);
Route::get('/booking/accommodation/customer/{tour}/{order}/{token}/{travellers}', [AccommodationController::class, 'getAccommodationBooking']);
Route::get('/booking/accommodation/tour/{tour}', [AccommodationController::class, 'getAccommodationInventoryForTour']);
Route::post('/booking/accommodation/reserve', [AccommodationController::class, 'postAccommodationReservation']);
//Route::post('/booking/accommodation/{tour}/{orders_customer}/{reference}/{accommodation_inventory}/{order}', [AccommodationController::class, 'postAccommodationBooking']);
Route::post('/booking/accommodation/delete', [AccommodationController::class, 'deleteAccommodationReservation']);
// accommodation rooms
Route::get('/accommodation/rooms/tour/{tour}/{order_id}', [AccommodationController::class, 'loadRoomsForTour']);
// Route::post('/booking/get/accommodation', [AccommodationController::class, 'getAccommodationBooking']);

// Events
Route::get('/booking/events',           [TourController::class, 'getEvents']);
Route::get('/booking/tours/{event_id}', [TourController::class, 'getTours']);
Route::get('/booking/tour/{id}',        [TourController::class, 'getBasicTourInformation']);

// Airlines
Route::get('/booking/airlines',         [AirlinesController::class, 'getAirlines']);
Route::get('/booking/airports',         [AirlinesController::class, 'getAirports']);

// Travellers
Route::get('/booking/tourparty', [CustomerController::class, 'getTravellers']);
Route::get('/booking/customer/{token}', [CustomerController::class, 'getCustomerByToken']);

// Flights
Route::get('/booking/flight/orders/{order_id}', [FlightController::class, 'loadFlightsForOrder']);
Route::get('/booking/flights/{tour_id}/{flight_type}', [FlightController::class, 'getFlightInventoriesForTour']);
Route::get('/booking/flights/{tour_id}', [FlightController::class, 'getFlightInventoriesForTour']);
Route::get('/booking/flight-inventories', [FlightController::class, 'getFlightsInventories']);
Route::get('/booking/flights/airport/{airport}', [FlightController::class, 'getFlightsFromAirport']);
// function removeCustomerOrderDetail($componentType, $order, $orderCustomer, $type, $custom, $reference)
// Route::post('/booking/flights/remove/flight/{order_id}/{order_customer_id}/{component_type}/{custom}/{inventory_tour_id}', [BookingController::class, 'removeFlightBooking']);
Route::post('/booking/flights/remove/flight', [BookingController::class, 'removeFlightBooking']);

// POST routes (requires token auth)

// store travellers
Route::post('/booking/lead-traveller', [BookingCustomerController::class, 'leadTraveller']);
Route::post('/booking/additional-traveller', [BookingCustomerController::class, 'additionalTraveller']);
Route::post('/booking/additional-traveller/remove', [BookingCustomerController::class, 'removeAdditionalTraveller']);

// is email registered 
Route::get('/booking/email/registered/{email}', [BookingCustomerController::class, 'checkActiveUser']);
Route::post('/booking/email/login', [BookingCustomerController::class, 'loginActiveUser']);
Route::post('/booking/authenticate/user', [BookingCustomerController::class, 'authenticate']);

// create Booking Order
Route::get('/booking/auth/token/{email}', [BookingCustomerController::class, 'salt']);
Route::post('/booking/set-login-token', [BookingCustomerController::class, 'updateLoginToken']);
Route::post('/booking/create-order', [BookingController::class, 'createOrder']);

// create Flights Order
// Route::post('/booking/flight/{customer}/{tour}/{order}/{flight_type}/{flight}/{custom}/{reference}', [
//     BookingController::class, 'bookFlightDetails'
// ]);
Route::post('/booking/flight', [BookFlightDetailsController::class, 'bookFlightDetails']);

// Booking Orders in Customer Order Details
// Route::get('/booking/findOrderByEmail/{email}', [CustomerController::class, 'getCustomerOrdersByEmail']);
Route::post('/booking/recover/token', [CustomerController::class, 'getTokenLink']);
Route::get('/booking/accomodation', [ApiController::class, 'getAccommodationFromTour']);
Route::get('/booking/payment-schedules', [PaymentController::class, 'getPaymentSchedules']);
Route::get('/booking/payment-schedule/{id}', [PaymentController::class, 'getPaymentSchedule']);
// existing components
Route::get('/orders/accommodation/{oCustomerId}/available', [TourComponentController::class, 'getAvailableAccommodationAddons'])->name('getAvailableAccommodationAddons');
Route::get('/orders/activities/{oCustomerId}/available', [TourComponentController::class, 'getAvailableActivityAddons'])->name('getAvailableActivityAddons');
Route::get('/orders/flights/{oCustomerId}/available', [TourComponentController::class, 'getAvailableFlightAddons'])->name('getAvailableFlightAddons');
Route::get('/orders/transports/{oCustomerId}/available', [TourComponentController::class, 'getAvailableTransportAddons'])->name('getAvailableTransportAddons');
// additional components for COD 
Route::post('/orders/accommodation/add', [TourComponentController::class, 'addAccommodationAddon'])->name('addAccommodationAddon');
Route::post('/orders/activity/add', [TourComponentController::class, 'addActivityAddon'])->name('addActivityAddon');
Route::post('/orders/flight/add', [TourComponentController::class, 'addFlightAddon'])->name('addFlightAddon');
Route::post('/orders/transport/add', [TourComponentController::class, 'addTransportAddon'])->name('addTransportAddon');
/**
 * end of CUSTOMER ORDER DETAILS Token access Booking Form
 */

// main ordering sytem: requires AUTH
Route::middleware('auth:api')->group(function() {
    Route::get('/booking/info', [BookingController::class, 'getInfo']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });   
});

Route::prefix('select')->group(function () {
   Route::get('locations', [SelectController::class, 'getLocations'])->name('api.locations.select');
   Route::get('regions', [SelectController::class, 'getRegions'])->name('api.regions.select');
   Route::get('countries', [SelectController::class, 'getCountries'])->name('api.countries.select');
   Route::get('location-types', [SelectController::class, 'getLocationTypes'])->name('api.location-types.select');
   Route::get('room-types', [SelectController::class, 'getRoomTypes'])->name('api.room-types.select');
   Route::get('board-types', [SelectController::class, 'getBoardTypes'])->name('api.board-types.select');
   Route::get('transport-types', [SelectController::class, 'getTransportTypes'])->name('api.transport-types.select');
   Route::get('operators', [SelectController::class, 'getOperators'])->name('api.operators.select');
   Route::get('travel-classes', [SelectController::class, 'getTravelClasses'])->name('api.travel-classes.select');
   Route::get('activity-types', [SelectController::class, 'getActivityTypes'])->name('api.activity-types.select');
   Route::get('ticket-types', [SelectController::class, 'getTicketTypes'])->name('api.ticket-types.select');
   Route::get('events', [SelectController::class, 'getEvents'])->name('api.events.select');
   Route::get('tours', [SelectController::class, 'getTours'])->name('api.tours.select');
   Route::get('airports', [SelectController::class, 'getAirports'])->name('api.airports.select');
   Route::get('airlines', [SelectController::class, 'getAirlines'])->name('api.airlines.select');
   Route::get('quotes', [SelectController::class, 'getQuotes'])->name('api.quotes.select');
   Route::get('customer', [SelectController::class, 'getCustomers'])->name('api.customers.select');
   Route::get('hat-size', [SelectController::class, 'getHatSizes'])->name('api.hat-size.select');
   Route::get('t-shirt-size', [SelectController::class, 'getTShirtSizes'])->name('api.t-shirt-size.select');
   Route::get('payment-method', [SelectController::class, 'getPaymentMethods'])->name('api.payment-method.select');
   Route::prefix('inventory')->group(function () {
       Route::get('accommodation', [SelectController::class, 'getAccommodationInventory'])->name('api.inventory.accommodation.select');
       Route::get('activity', [SelectController::class, 'getActivityInventory'])->name('api.inventory.activity.select');
       Route::get('flight', [SelectController::class, 'getFlightInventory'])->name('api.inventory.flight.select');
       Route::get('transport', [SelectController::class, 'getTransportInventory'])->name('api.inventory.transport.select');
   });
   Route::prefix('selected')->group(function () {
       Route::get('location/{id}', [SelectController::class, 'getSelectedLocation'])->name('api.locations.selected');
       Route::get('region/{id}', [SelectController::class, 'getSelectedRegion'])->name('api.regions.selected');
       Route::get('country/{id}', [SelectController::class, 'getSelectedCountry'])->name('api.countries.selected');
       Route::get('location-type/{id}', [SelectController::class, 'getSelectedLocationType'])->name('api.location-types.selected');
       Route::get('room-type/{id}', [SelectController::class, 'getSelectedRoomType'])->name('api.room-types.selected');
       Route::get('board-type/{id}', [SelectController::class, 'getSelectedBoardType'])->name('api.board-types.selected');
       Route::get('transport-type/{id}', [SelectController::class, 'getSelectedTransportType'])->name('api.transport-types.selected');
       Route::get('operator/{id}', [SelectController::class, 'getSelectedOperator'])->name('api.operators.selected');
       Route::get('travel-class/{id}', [SelectController::class, 'getSelectedTravelClass'])->name('api.travel-classes.selected');
       Route::get('activity-type/{id}', [SelectController::class, 'getSelectedActivityType'])->name('api.activity-types.selected');
       Route::get('ticket-type/{id}', [SelectController::class, 'getSelectedTicketTypes'])->name('api.ticket-types.selected');
       Route::get('event/{id}', [SelectController::class, 'getSelectedEvent'])->name('api.events.selected');
       Route::get('tour/{id}', [SelectController::class, 'getSelectedTour'])->name('api.tours.selected');
       Route::get('airports/{id}', [SelectController::class, 'getSelectedAirport'])->name('api.airports.selected');
       Route::get('airlines/{id}', [SelectController::class, 'getSelectedAirline'])->name('api.airlines.selected');
       Route::get('quotes/{id}', [SelectController::class, 'getSelectedQuote'])->name('api.quotes.selected');
       Route::get('customers/{id}', [SelectController::class, 'getSelectedCustomer'])->name('api.customers.selected');
       Route::get('hat-size/{id}', [SelectController::class, 'getSelectedHatSize'])->name('api.hat-size.selected');
       Route::get('t-shirt-size/{id}', [SelectController::class, 'getSelectedTShirtSize'])->name('api.t-shirt-size.selected');
       Route::get('payment-method/{id}', [SelectController::class, 'getSelectedPaymentMethod'])->name('api.payment-method.selected');
       Route::prefix('inventory/{id}')->group(function () {
           Route::get('accommodation', [SelectController::class, 'getSelectedAccommodationInventory'])->name('api.inventory.accommodation.selected');
           Route::get('activity', [SelectController::class, 'getSelectedActivityInventory'])->name('api.inventory.activity.selected');
           Route::get('flight', [SelectController::class, 'getSelectedFlightInventory'])->name('api.inventory.flight.selected');
           Route::get('transport', [SelectController::class, 'getSelectedTransportInventory'])->name('api.inventory.transport.selected');
       });
   });
});

Route::prefix('datatables')->group(function () {
   Route::get('accommodation-inventory/{tour}', [DataTablesController::class, 'getAccommodationInventoryComponents'])->name('api.accommodation-inventory.datatables');
   Route::get('activity-inventory/{tour}', [DataTablesController::class, 'getActivityInventoryComponents'])->name('api.activity-inventory.datatables');
   Route::get('flight-inventory/{tour}', [DataTablesController::class, 'getFlightInventoryComponents'])->name('api.flight-inventory.datatables');
   Route::get('transport-inventory/{tour}', [DataTablesController::class, 'getTransportInventoryComponents'])->name('api.transport-inventory.datatables');
});

Route::prefix('component')->group(function() {
    Route::prefix('tour/{tour}')->group(function() {
        Route::prefix('accommodation/inventory')->group(function() {
           Route::post('/add', [AccommodationController::class, 'addAccommodationInventoryToTour'])->name('api.tour.accommodation.inventory.add');
        });
        Route::prefix('activity/inventory')->group(function() {
            Route::post('/add', [ActivityController::class, 'addActivityInventoryToTour'])->name('api.tour.activity.inventory.add');
        });
        Route::prefix('flight/inventory')->group(function() {
            Route::post('/add', [FlightController::class, 'addFlightInventoryToTour'])->name('api.tour.flight.inventory.add');
        });
        Route::prefix('transport/inventory')->group(function() {
            Route::post('/add', [TransportController::class, 'addTransportInventoryToTour'])->name('api.tour.transport.inventory.add');
        });
    });
});
