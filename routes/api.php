<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Api\CustomerBookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\SelectController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\AirlinesController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\TransportController;
use App\Http\Controllers\Api\ActivitiesController;
use App\Http\Controllers\Api\CountryApiController;
use App\Http\Controllers\Api\DataTablesController;
use App\Http\Controllers\Api\AccommodationController;
use App\Http\Controllers\Api\TourComponentController;
use App\Http\Controllers\Api\BookingCustomerController;
use App\Http\Controllers\Api\CustomerComponentController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\BespokeReportController;
use App\Repository\OrderRepository;

/**
 * Booking form routes are PUBLIC (do not use api auth)
 */
// Booking Repo
Route::prefix('booking')->group(function () {

    Route::get('/customer/bookings/{token}', [BookingController::class, 'collect']);
    Route::get('/token/{token}', [BookingController::class, 'get']);
    Route::post('/create', [BookingController::class, 'create']);

    // Activities
    Route::get('/activities/tour/{tour}', [ActivitiesController::class, 'getActivitiesInventoryForTour']);
    Route::get('/activities/booking/{token}/tour/{tour}', [ActivitiesController::class, 'getActivitiesBooking']);
    Route::post('/activities/booking', [ActivitiesController::class, 'updateActivities']);

    // Transports
    Route::get('/transports/tour/{tour}', [TransportController::class, 'getTransportsInventoryForTour']);
    Route::get('/transports/booking/{token}/tour/{tour}', [TransportController::class, 'getTransportsBooking']);
    Route::post('/transports/booking', [TransportController::class, 'updateTransports']);

    // Accommodation
    Route::get('/accommodation/groups', [AccommodationController::class, 'getAccommodationGroups']);
    Route::get('/accommodation/inventory/tour/{tour}', [AccommodationController::class, 'getAccommodationInventoryForTour']);
    Route::get('/accommodation/tour/{tour}', [AccommodationController::class, 'getAccommodationInventoryForTour']);
    Route::post('/accommodation/reserve', [AccommodationController::class, 'postAccommodationReservation']);
    Route::post('/accommodation/reset', [AccommodationController::class, 'deleteAccommodationReservation']);
    Route::post('/accommodation/delete', [AccommodationController::class, 'deleteAccommodationReservation']);

    // accommodation rooms
    //Route::get('/accommodation/options/{tour}', [AccommodationController::class, 'getAccommodationOptions']);
    Route::get('/accommodation/booking/{token}/tour/{tour}', [AccommodationController::class, 'getAccommodationBooking']);
    Route::get('/accommodation/rooms/tour/{tour}', [AccommodationController::class, 'loadRoomsForTour']);
    // Route::post('/booking/get/accommodation', [AccommodationController::class, 'getAccommodationBooking']);
    Route::get('/tour/price/{tour_id}', [TourController::class, 'getTourPrice']);

    // Airlines
    Route::get('/airlines', [AirlinesController::class, 'getAirlines']);
    Route::get('/airports', [AirlinesController::class, 'getAirports']);

    // Travellers
    Route::get('/customer/{token}', [CustomerController::class, 'getCustomerByToken']);
    Route::get('/travellers/{token}', [BookingCustomerController::class, 'loadTravellers']);

    // Flights
    Route::get('/flight/bookings/{booking_token}/{type?}', [FlightController::class, 'loadFlightsForBooking']);
    Route::get('/flights/tour/{tour_id}/{flight_type}', [FlightController::class, 'getFlightInventoriesForTour']);
    Route::get('/flights/tour/{tour_id}', [FlightController::class, 'getFlightInventoriesForTour']);
    Route::get('/flight-inventories', [FlightController::class, 'getFlightsInventories']);
    Route::get('/flights/airport/{airport}', [FlightController::class, 'getFlightsFromAirport']);

    Route::post('/flight', [FlightController::class, 'postFlightBooking']);
    Route::post('/flights/remove/flight', [BookingController::class, 'removeFlightBooking']);

    // Booking summary for payment
    Route::get('/summary/{token}/gather', [BookingController::class, 'gatherDetails']);

    // store travellers
    Route::post('/lead-traveller', [BookingCustomerController::class, 'leadTraveller']);
    Route::post('/additional-traveller', [BookingCustomerController::class, 'bookingTraveller']);
    Route::post('/additional-traveller/remove', [BookingCustomerController::class, 'removeBookingTraveller']);
    Route::post('/customer/email/check', [CustomerController::class, 'findCustomerByEmail']);

    // is email registered
    Route::get('/email/registered/{email}', [BookingCustomerController::class, 'checkActiveUser']);
    Route::post('/email/login', [BookingCustomerController::class, 'loginActiveUser']);
    Route::post('/authenticate/user', [BookingCustomerController::class, 'authenticate']);

    // create Booking Order
    Route::get('/auth/token/{email}', [BookingCustomerController::class, 'salt']);
    Route::post('/create-booking', [BookingController::class, 'create']);
    Route::post('/name/update', [BookingController::class, 'update']);
    Route::post('/deposit/calculate', [BookingController::class, 'calculateDeposit']);
    
    Route::post('/deposit/payment', [BookingController::class, 'payDeposit']);


    // Events (not currently supported, protect with middleware: public tours are obtained by tour name only)
    Route::get('/events', [TourController::class, 'getEvents']);
    Route::get('/tours/{event_id?}', [TourController::class, 'getTours']);
    Route::get('/tour/{id}', [TourController::class, 'getTour']);
    
    // Route::post('/set-login-token', [BookingCustomerController::class, 'updateLoginToken']);
    // Route::post('/create-order', [BookingController::class, 'createOrder']);

    // create Flights Order
    // Route::post('/flight/{customer}/{tour}/{order}/{flight_type}/{flight}/{custom}/{reference}', [
    // BookingController::class, 'bookFlightDetails'
    // ]);


    // Booking Orders in Customer Order Details
    // Route::get('/findOrderByEmail/{email}', [CustomerController::class, 'getCustomerOrdersByEmail']);
    Route::post('/recover/token', [CustomerController::class, 'getTokenLink']);
    Route::get('/accomodation', [ApiController::class, 'getAccommodationFromTour']);
    // Route::get('/payment-schedules', [PaymentController::class, 'getPaymentSchedules']);
    // Route::get('/payment-schedule/{id}', [PaymentController::class, 'getPaymentSchedule']);
    Route::get('/countries', [CountryApiController::class, 'getCountries']);
});

Route::prefix('/orders')->group(function () {
    // existing components
    Route::get('/accommodation/{oCustomerId}/available', [TourComponentController::class, 'getAvailableAccommodationAddons'])->name('getAvailableAccommodationAddons');
    Route::get('/activities/{oCustomerId}/available', [TourComponentController::class, 'getAvailableActivityAddons'])->name('getAvailableActivityAddons');
    Route::get('/flights/{oCustomerId}/available', [TourComponentController::class, 'getAvailableFlightAddons'])->name('getAvailableFlightAddons');
    Route::get('/transports/{oCustomerId}/available', [TourComponentController::class, 'getAvailableTransportAddons'])->name('getAvailableTransportAddons');
    // additional components for COD
    Route::post('/accommodation/add', [TourComponentController::class, 'addAccommodationAddon'])->name('addAccommodationAddon');
    Route::post('/activity/add', [TourComponentController::class, 'addActivityAddon'])->name('addActivityAddon');
    Route::post('/flight/add', [TourComponentController::class, 'addFlightAddon'])->name('addFlightAddon');
    Route::post('/transport/add', [TourComponentController::class, 'addTransportAddon'])->name('addTransportAddon');
    /**
     * end of CUSTOMER ORDER DETAILS Token access Booking Form
     */
});

Route::stripeWebhooks('/stripe/webhooks');

Route::middleware('auth:api')->group(function() {
    Route::get('/booking/info', [BookingController::class, 'getInfo']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });   
});

Route::post('/dual/select/countries', [SelectController::class, 'getCountries'])->name('api.countries.select');
Route::post('/php/booking/upgrade/activity/{token}', [CustomerBookingController::class, 'upgradeActivity'])->name('api.booking.upgrade-activity');

Route::middleware('api.token.both')->name('api.')->prefix('dual')->group(function () {
    Route::prefix('select')->group(function () {

        Route::post('hat-size', [SelectController::class, 'getHatSizes'])->name('hat-size.select');
        Route::post('t-shirt-size', [SelectController::class, 'getTShirtSizes'])->name('t-shirt-size.select');
        Route::post('available-merchandise/{orderCustomer}', [SelectController::class, 'getAvailableMerchandise'])->name('available-merchandise.select');
        Route::post('available-accommodation/{orderCustomer}', [SelectController::class, 'getAvailableAccommodation'])->name('available-accommodation.select');
        Route::post('available-activities/{orderCustomer}', [SelectController::class, 'getAvailableActivities'])->name('available-activities.select');
        Route::post('available-flights/{orderCustomer}', [SelectController::class, 'getAvailableFlights'])->name('available-flights.select');
        Route::post('available-transports/{orderCustomer}', [SelectController::class, 'getAvailableTransport'])->name('available-transports.select');
        Route::prefix('selected')->group(function () {
            Route::post('country/{id}', [SelectController::class, 'getSelectedCountry'])->name('countries.selected');
            Route::post('hat-size/{id}', [SelectController::class, 'getSelectedHatSize'])->name('hat-size.selected');
            Route::post('t-shirt-size/{id}', [SelectController::class, 'getSelectedTShirtSize'])->name('t-shirt-size.selected');
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
        });
        Route::post('accommodation/upgrade', [CustomerComponentController::class, 'applyAccommodationUpgrade'])->name('customer.accommodation.upgrade');
        Route::post('accommodation/upgrade/buy', [CustomerComponentController::class, 'purchaseAccommodationUpgrade'])->name('customer.accommodation.upgrade.purchase');
        Route::post('activity/upgrade', [CustomerComponentController::class, 'applyActivityUpgrade'])->name('customer.activity.upgrade');
        Route::post('activity/upgrade/buy', [CustomerComponentController::class, 'purchaseActivityUpgrade'])->name('customer.activity.upgrade.purchase');
        Route::post('flight/upgrade', [CustomerComponentController::class, 'applyFlightUpgrade'])->name('customer.flight.upgrade');
        Route::post('flight/upgrade/buy', [CustomerComponentController::class, 'purchaseFlightUpgrade'])->name('customer.flight.upgrade.purchase');
        Route::post('transport/upgrade', [CustomerComponentController::class, 'applyTransportUpgrade'])->name('customer.transport.upgrade');
        Route::post('transport/upgrade/buy', [CustomerComponentController::class, 'purchaseTransportUpgrade'])->name('customer.transport.upgrade.purchase');
    });
});

Route::middleware('api.token.auth')->name('api.')->group(function () {
    Route::post('accommodation/rooming/{order}/save', [AccommodationController::class, 'saveRoomingData'])->name('roomings.save');
    Route::prefix('select')->group(function () {
        Route::post('locations', [SelectController::class, 'getLocations'])->name('locations.select');
        Route::post('addresses', [SelectController::class, 'getAddresses'])->name('addresses.select');
        Route::post('currencies', [SelectController::class, 'getCurrencies'])->name('currencies.select');
        Route::post('addresses', [SelectController::class, 'getAddresses'])->name('addresses.select');
        Route::post('currencies', [SelectController::class, 'getCurrencies'])->name('currencies.select');
        Route::post('regions', [SelectController::class, 'getRegions'])->name('regions.select');
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
        Route::post('customer/{order}', [SelectController::class, 'getAvailableCustomers'])->name('available-customers.select');
        Route::post('payment-method', [SelectController::class, 'getPaymentMethods'])->name('payment-method.select');
        Route::post('tour-category', [SelectController::class, 'getTourCategories'])->name('tour-categories.select');
        Route::prefix('inventory')->group(function () {
            Route::post('accommodation', [SelectController::class, 'getAccommodationInventory'])->name('inventory.accommodation.select');
            Route::post('activity', [SelectController::class, 'getActivityInventory'])->name('inventory.activity.select');
            Route::post('flight', [SelectController::class, 'getFlightInventory'])->name('inventory.flight.select');
            Route::post('transport', [SelectController::class, 'getTransportInventory'])->name('inventory.transport.select');
            Route::prefix('{inventoryTour}')->group(function () {
                Route::post('accommodation', [SelectController::class, 'getAccommodationInventoryForUpgrade'])->name('inventory.accommodation.upgrade.select');
                Route::post('activity', [SelectController::class, 'getActivityInventoryForUpgrade'])->name('inventory.activity.upgrade.select');
                Route::post('flight', [SelectController::class, 'getFlightInventoryForUpgrade'])->name('inventory.flight.upgrade.select');
                Route::post('transport', [SelectController::class, 'getTransportInventoryForUpgrade'])->name('inventory.transport.upgrade.select');
            });
        });
        Route::prefix('selected')->group(function () {
            Route::post('location/{id}', [SelectController::class, 'getSelectedLocation'])->name('locations.selected');
            Route::post('address/{id}', [SelectController::class, 'getSelectedAddress'])->name('addresses.selected');
            Route::post('currency/{id}', [SelectController::class, 'getSelectedCurrency'])->name('currencies.selected');
            Route::post('region/{id}', [SelectController::class, 'getSelectedRegion'])->name('regions.selected');
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
            Route::post('payment-method/{id}', [SelectController::class, 'getSelectedPaymentMethod'])->name('payment-method.selected');
            Route::post('tour-category/{id}', [SelectController::class, 'getSelectedTourCategory'])->name('tour-categories.selected');
            Route::prefix('inventory/{id}')->group(function () {
                Route::post('accommodation', [SelectController::class, 'getSelectedAccommodationInventory'])->name('inventory.accommodation.selected');
                Route::post('activity', [SelectController::class, 'getSelectedActivityInventory'])->name('inventory.activity.selected');
                Route::post('flight', [SelectController::class, 'getSelectedFlightInventory'])->name('inventory.flight.selected');
                Route::post('transport', [SelectController::class, 'getSelectedTransportInventory'])->name('inventory.transport.selected');
                Route::prefix('upgrade')->group(function () {
                    Route::post('accommodation', [SelectController::class, 'getSelectedAccommodationInventoryForUpgrade'])->name('inventory.accommodation.upgrade.selected');
                    Route::post('activity', [SelectController::class, 'getSelectedActivityInventoryForUpgrade'])->name('inventory.activity.upgrade.selected');
                    Route::post('flight', [SelectController::class, 'getSelectedFlightInventoryForUpgrade'])->name('inventory.flight.upgrade.selected');
                    Route::post('transport', [SelectController::class, 'getSelectedTransportInventoryForUpgrade'])->name('inventory.transport.upgrade.selected');
                });
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
            Route::prefix('available')->name('get.')->group(function () {
                Route::get('/accommodation/{oCustomerId}', [TourComponentController::class, 'getAvailableAccommodationAddons'])->name('accommodation');
                Route::get('/activities/{oCustomerId}', [TourComponentController::class, 'getAvailableActivityAddons'])->name('activity');
                Route::get('/flights/{oCustomerId}', [TourComponentController::class, 'getAvailableFlightAddons'])->name('flight');
                Route::get('/transports/{oCustomerId}', [TourComponentController::class, 'getAvailableTransportAddons'])->name('transport');
            });
        });
        Route::post('accommodation/upgrade', [TourComponentController::class, 'applyAccommodationUpgrade'])->name('accommodation.upgrade');
        Route::post('activity/upgrade', [TourComponentController::class, 'applyActivityUpgrade'])->name('activity.upgrade');
        Route::post('flight/upgrade', [TourComponentController::class, 'applyFlightUpgrade'])->name('flight.upgrade');
        Route::post('transport/upgrade', [TourComponentController::class, 'applyTransportUpgrade'])->name('transport.upgrade');
        // Hack method to get route in order screen. TODO: Better solution?
        Route::post('/status/{order}', [OrderController::class, 'getOrderStatus'])->name('status');
        Route::get('/status', function(){})->name('status.stub');
    });

    Route::prefix('reports')->name('reports.')->group(function() {
       Route::post('bespoke/save', [BespokeReportController::class, 'store'])->name('bespoke.save');
       Route::post('bespoke/export', [BespokeReportController::class, 'apiExport'])->name('bespoke.export');
    });
});
