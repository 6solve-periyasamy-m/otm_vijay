<?php

use App\Http\Controllers\Api\DataTablesController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SelectController;
use App\Http\Controllers\Api\TourComponentController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\TransportController;
use App\Repository\OrderRepository;
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

Route::middleware('auth:api')->group(function() {
    
});

Route::middleware('api.token.auth')->name('api.')->group(function () {
    Route::prefix('select')->group(function () {
        Route::post('locations', [SelectController::class, 'getLocations'])->name('locations.select');
        Route::post('addresses', [SelectController::class, 'getAddresses'])->name('addresses.select');
        Route::post('currencies', [SelectController::class, 'getCurrencies'])->name('currencies.select');
        Route::post('addresses', [SelectController::class, 'getAddresses'])->name('addresses.select');
        Route::post('currencies', [SelectController::class, 'getCurrencies'])->name('currencies.select');
        Route::post('regions', [SelectController::class, 'getRegions'])->name('regions.select');
        Route::post('countries', [SelectController::class, 'getCountries'])->name('countries.select');
        Route::post('location-types', [SelectController::class, 'getLocationTypes'])->name('location-types.select');
        Route::post('room-types', [SelectController::class, 'getRoomTypes'])->name('room-types.select');
        Route::post('board-types', [SelectController::class, 'getBoardTypes'])->name('board-types.select');
        Route::post('transport-types', [SelectController::class, 'getTransportTypes'])->name('transport-types.select');
        Route::post('operators', [SelectController::class, 'getOperators'])->name('operators.select');
        Route::post('travel-classes', [SelectController::class, 'getTravelClasses'])->name('travel-classes.select');
        Route::post('activity-types', [SelectController::class, 'getActivityTypes'])->name('activity-types.select');
        Route::post('ticket-types', [SelectController::class, 'getTicketTypes'])->name('ticket-types.select');
        Route::post('events', [SelectController::class, 'getEvents'])->name('events.select');
        Route::post('tours', [SelectController::class, 'getTours'])->name('tours.select');
        Route::post('airports', [SelectController::class, 'getAirports'])->name('airports.select');
        Route::post('airlines', [SelectController::class, 'getAirlines'])->name('airlines.select');
        Route::post('quotes', [SelectController::class, 'getQuotes'])->name('quotes.select');
        Route::post('customer', [SelectController::class, 'getCustomers'])->name('customers.select');
        Route::post('hat-size', [SelectController::class, 'getHatSizes'])->name('hat-size.select');
        Route::post('t-shirt-size', [SelectController::class, 'getTShirtSizes'])->name('t-shirt-size.select');
        Route::post('payment-method', [SelectController::class, 'getPaymentMethods'])->name('payment-method.select');
        Route::post('available-merchandise/{orderCustomer}', [SelectController::class, 'getAvailableMerchandise'])->name('available-merchandise.select');
        Route::post('tour-category', [SelectController::class, 'getTourCategories'])->name('tour-categories.select');
        Route::prefix('inventory')->group(function () {
            Route::post('accommodation', [SelectController::class, 'getAccommodationInventory'])->name('inventory.accommodation.select');
            Route::post('activity', [SelectController::class, 'getActivityInventory'])->name('inventory.activity.select');
            Route::post('flight', [SelectController::class, 'getFlightInventory'])->name('inventory.flight.select');
            Route::post('transport', [SelectController::class, 'getTransportInventory'])->name('inventory.transport.select');
        });
        Route::prefix('selected')->group(function () {
            Route::post('location/{id}', [SelectController::class, 'getSelectedLocation'])->name('locations.selected');
            Route::post('address/{id}', [SelectController::class, 'getSelectedAddress'])->name('addresses.selected');
            Route::post('currency/{id}', [SelectController::class, 'getSelectedCurrency'])->name('currencies.selected');
            Route::post('region/{id}', [SelectController::class, 'getSelectedRegion'])->name('regions.selected');
            Route::post('country/{id}', [SelectController::class, 'getSelectedCountry'])->name('countries.selected');
            Route::post('location-type/{id}', [SelectController::class, 'getSelectedLocationType'])->name('location-types.selected');
            Route::post('room-type/{id}', [SelectController::class, 'getSelectedRoomType'])->name('room-types.selected');
            Route::post('board-type/{id}', [SelectController::class, 'getSelectedBoardType'])->name('board-types.selected');
            Route::post('transport-type/{id}', [SelectController::class, 'getSelectedTransportType'])->name('transport-types.selected');
            Route::post('operator/{id}', [SelectController::class, 'getSelectedOperator'])->name('operators.selected');
            Route::post('travel-class/{id}', [SelectController::class, 'getSelectedTravelClass'])->name('travel-classes.selected');
            Route::post('activity-type/{id}', [SelectController::class, 'getSelectedActivityType'])->name('activity-types.selected');
            Route::post('ticket-type/{id}', [SelectController::class, 'getSelectedTicketTypes'])->name('ticket-types.selected');
            Route::post('event/{id}', [SelectController::class, 'getSelectedEvent'])->name('events.selected');
            Route::post('tour/{id}', [SelectController::class, 'getSelectedTour'])->name('tours.selected');
            Route::post('airports/{id}', [SelectController::class, 'getSelectedAirport'])->name('airports.selected');
            Route::post('airlines/{id}', [SelectController::class, 'getSelectedAirline'])->name('airlines.selected');
            Route::post('quotes/{id}', [SelectController::class, 'getSelectedQuote'])->name('quotes.selected');
            Route::post('customers/{id}', [SelectController::class, 'getSelectedCustomer'])->name('customers.selected');
            Route::post('hat-size/{id}', [SelectController::class, 'getSelectedHatSize'])->name('hat-size.selected');
            Route::post('t-shirt-size/{id}', [SelectController::class, 'getSelectedTShirtSize'])->name('t-shirt-size.selected');
            Route::post('payment-method/{id}', [SelectController::class, 'getSelectedPaymentMethod'])->name('payment-method.selected');
            Route::post('tour-category/{id}', [SelectController::class, 'getSelectedTourCategory'])->name('tour-categories.selected');
            Route::prefix('inventory/{id}')->group(function () {
                Route::post('accommodation', [SelectController::class, 'getSelectedAccommodationInventory'])->name('inventory.accommodation.selected');
                Route::post('activity', [SelectController::class, 'getSelectedActivityInventory'])->name('inventory.activity.selected');
                Route::post('flight', [SelectController::class, 'getSelectedFlightInventory'])->name('inventory.flight.selected');
                Route::post('transport', [SelectController::class, 'getSelectedTransportInventory'])->name('inventory.transport.selected');
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

    Route::prefix('orders')->name('order.')->group(function() {
        Route::prefix('addons')->name('addon.')->group(function () {
            Route::prefix('add')->name('add.')->group(function () {
                Route::post('/accommodation/add', [TourComponentController::class, 'addAccommodationAddon'])->name('accommodation');
                Route::post('/activity/add', [TourComponentController::class, 'addActivityAddon'])->name('activity');
                Route::post('/flight/add', [TourComponentController::class, 'addFlightAddon'])->name('flight');
                Route::post('/transport/add', [TourComponentController::class, 'addTransportAddon'])->name('transport');
                Route::post('/merchandise/add', [TourComponentController::class, 'addMerchandiseAddon'])->name('merchandise');
            });
            Route::prefix('available')->name('get.')->group(function () {
                Route::get('/accommodation/{oCustomerId}', [TourComponentController::class, 'getAvailableAccommodationAddons'])->name('accommodation');
                Route::get('/activities/{oCustomerId}', [TourComponentController::class, 'getAvailableActivityAddons'])->name('activity');
                Route::get('/flights/{oCustomerId}', [TourComponentController::class, 'getAvailableFlightAddons'])->name('flight');
                Route::get('/transports/{oCustomerId}', [TourComponentController::class, 'getAvailableTransportAddons'])->name('transport');
            });
        });
        // Hack method to get route in order screen. TODO: Better solution?
        Route::post('/status/{order}', [OrderController::class, 'getOrderStatus'])->name('status');
        Route::get('/status', function(){})->name('status.stub');
    });
});

Route::get('/customer/finances', function () { return OrderRepository::getCustomerOrders(\App\Models\Customer::findOrFail(1)); });
// TODO: Remove
