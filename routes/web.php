<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingFormLoginController;
use App\Http\Controllers\Models\AccommodationController;
use App\Http\Controllers\Models\AccommodationInventoryController;
use App\Http\Controllers\Models\AccommodationInventoryTourController;
use App\Http\Controllers\Models\ActivityController;
use App\Http\Controllers\Models\ActivityInventoryController;
use App\Http\Controllers\Models\ActivityInventoryTourController;
use App\Http\Controllers\Models\ActivityTypeController;
use App\Http\Controllers\Models\AddressController;
use App\Http\Controllers\Models\AirlineController;
use App\Http\Controllers\Models\AirportController;
use App\Http\Controllers\Models\BoardTypeController;
use App\Http\Controllers\Models\CountryController;
use App\Http\Controllers\Models\CustomerController;
use App\Http\Controllers\Models\EventController;
use App\Http\Controllers\Models\FlightController;
use App\Http\Controllers\Models\FlightInventoryController;
use App\Http\Controllers\Models\FlightInventoryTourController;
use App\Http\Controllers\Models\HatSizeController;
use App\Http\Controllers\Models\LocationController;
use App\Http\Controllers\Models\LocationTypeController;
use App\Http\Controllers\Models\ManualAdjustmentController;
use App\Http\Controllers\Models\OperatorController;
use App\Http\Controllers\Models\OrderController;
use App\Http\Controllers\Models\OrderCustomerAdjustmentController;
use App\Http\Controllers\Models\OrdersCustomerController;
use App\Http\Controllers\Models\PaymentController;
use App\Http\Controllers\Models\PaymentMethodController;
use App\Http\Controllers\Models\RegionController;
use App\Http\Controllers\Models\RoomTypeController;
use App\Http\Controllers\Models\TicketTypeController;
use App\Http\Controllers\Models\TransportController;
use App\Http\Controllers\Models\TransportInventoryController;
use App\Http\Controllers\Models\TransportInventoryTourController;
use App\Http\Controllers\Models\TransportTypeController;
use App\Http\Controllers\Models\TravelClassController;
use App\Http\Controllers\Models\TShirtSizeController;
use App\Http\Controllers\OrderComponentController;
use App\Http\Controllers\OrderCustomerController;
use App\Http\Controllers\OrderSystemController;
use App\Http\Controllers\PaymentScheduleController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TourController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('pages.otm');
});

Route::get('/homepage', function () {
    return view('pages.homepage');
});

Route::get('/pdfmake', function () {
    return view('pdf.atol');
});

Route::prefix("/booking")->group(function () {

    // debugging routes
    Route::get('/check/events', [TourController::class, 'getEvents']);
    Route::get('/check/tour/{event_id}', [TourController::class, 'getTours']);
    Route::get('/vuetest', function () {
        return view('tests.vue');
    });

    Route::get('/store', function () {
        return view('pages.booking.store');
    });
    Route::get('/login/{token}', [BookingFormLoginController::class, 'loginWithToken']); // Demo for now
    Route::get('/edit/{id}', [BookingController::class, 'bookingForm']);
    Route::get('/tour/{url}', [BookingController::class, 'bookingForm']);
    Route::get('/event/{url}', [BookingController::class, 'eventBookingForm']);
    Route::get('/', [BookingController::class, 'bookingForm']);

    Route::get('/{url}', [BookingController::class, 'tourBookingForm']);

});

Route::get('phones', function () {
    return view('tests.validation.phone');
});

Route::prefix('customer')->group(function () {
    Route::get('/payment/schedule', [PaymentScheduleController::class, 'index'])->name('payment-schedule');
});

Route::get('/dashboard', function () {
    return view('pages.dashboard');
});

Route::prefix('raw')->middleware('auth')->group(function () {
    Route::prefix('activity-types')->group(function () {
        Route::get('/', [ActivityTypeController::class, 'index'])->name('activity-types.all');
        Route::get('/create', [ActivityTypeController::class, 'create'])->name('activity-types.create');
        Route::post('/create', [ActivityTypeController::class, 'store'])->name('activity-types.store');
        Route::get('/{activityType}', [ActivityTypeController::class, 'view'])->name('activity-types.view');
        Route::get('/update/{activityType}', [ActivityTypeController::class, 'edit'])->name('activity-types.edit');
        Route::post('/update/{activityType}', [ActivityTypeController::class, 'update'])->name('activity-types.update');
        Route::post('/delete/{activityType}', [ActivityTypeController::class, 'destroy'])->name('activity-types.delete');
    });
    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('addresses.all');
        Route::get('/create', [AddressController::class, 'create'])->name('addresses.create');
        Route::post('/create', [AddressController::class, 'store'])->name('addresses.store');
        Route::get('/{address}', [AddressController::class, 'view'])->name('addresses.view');
        Route::get('/update/{address}', [AddressController::class, 'edit'])->name('addresses.edit');
        Route::post('/update/{address}', [AddressController::class, 'update'])->name('addresses.update');
        Route::post('/delete/{address}', [AddressController::class, 'destroy'])->name('addresses.delete');
    });
    Route::prefix('airlines')->group(function () {
        Route::get('/', [AirlineController::class, 'index'])->name('airlines.all');
        Route::get('/create', [AirlineController::class, 'create'])->name('airlines.create');
        Route::post('/create', [AirlineController::class, 'store'])->name('airlines.store');
        Route::get('/{airline}', [AirlineController::class, 'view'])->name('airlines.view');
        Route::get('/update/{airline}', [AirlineController::class, 'edit'])->name('airlines.edit');
        Route::post('/update/{airline}', [AirlineController::class, 'update'])->name('airlines.update');
        Route::post('/delete/{airline}', [AirlineController::class, 'destroy'])->name('airlines.delete');
    });
    Route::prefix('airports')->group(function () {
        Route::get('/', [AirportController::class, 'index'])->name('airports.all');
        Route::get('/create', [AirportController::class, 'create'])->name('airports.create');
        Route::post('/create', [AirportController::class, 'store'])->name('airports.store');
        Route::get('/{airport}', [AirportController::class, 'view'])->name('airports.view');
        Route::get('/update/{airport}', [AirportController::class, 'edit'])->name('airports.edit');
        Route::post('/update/{airport}', [AirportController::class, 'update'])->name('airports.update');
        Route::post('/delete/{airport}', [AirportController::class, 'destroy'])->name('airports.delete');
    });
    Route::prefix('board-types')->group(function () {
        Route::get('/', [BoardTypeController::class, 'index'])->name('board-types.all');
        Route::get('/create', [BoardTypeController::class, 'create'])->name('board-types.create');
        Route::post('/create', [BoardTypeController::class, 'store'])->name('board-types.store');
        Route::get('/{boardType}', [BoardTypeController::class, 'view'])->name('board-types.view');
        Route::get('/update/{boardType}', [BoardTypeController::class, 'edit'])->name('board-types.edit');
        Route::post('/update/{boardType}', [BoardTypeController::class, 'update'])->name('board-types.update');
        Route::post('/delete/{boardType}', [BoardTypeController::class, 'destroy'])->name('board-types.delete');
    });
    Route::prefix('countries')->group(function () {
        Route::get('/', [CountryController::class, 'index'])->name('countries.all');
        Route::get('/create', [CountryController::class, 'create'])->name('countries.create');
        Route::post('/create', [CountryController::class, 'store'])->name('countries.store');
        Route::get('/{country}', [CountryController::class, 'view'])->name('countries.view');
        Route::get('/update/{country}', [CountryController::class, 'edit'])->name('countries.edit');
        Route::post('/update/{country}', [CountryController::class, 'update'])->name('countries.update');
        Route::post('/delete/{country}', [CountryController::class, 'destroy'])->name('countries.delete');
    });
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.all');
        Route::get('/create', [CustomerController::class, 'create'])->name('customers.create');
        Route::post('/create', [CustomerController::class, 'store'])->name('customers.store');
        Route::get('/{customer}', [CustomerController::class, 'view'])->name('customers.view');
        Route::get('/update/{customer}', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::post('/update/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::post('/delete/{customer}', [CustomerController::class, 'destroy'])->name('customers.delete');
    });
    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('events.all');
        Route::get('/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/create', [EventController::class, 'store'])->name('events.store');
        Route::get('/{event}', [EventController::class, 'view'])->name('events.view');
        Route::get('/update/{event}', [EventController::class, 'edit'])->name('events.edit');
        Route::post('/update/{event}', [EventController::class, 'update'])->name('events.update');
        Route::post('/delete/{event}', [EventController::class, 'destroy'])->name('events.delete');
    });
    Route::prefix('hat-sizes')->group(function () {
        Route::get('/', [HatSizeController::class, 'index'])->name('hat-sizes.all');
        Route::get('/create', [HatSizeController::class, 'create'])->name('hat-sizes.create');
        Route::post('/create', [HatSizeController::class, 'store'])->name('hat-sizes.store');
        Route::get('/{hatSize}', [HatSizeController::class, 'view'])->name('hat-sizes.view');
        Route::get('/update/{hatSize}', [HatSizeController::class, 'edit'])->name('hat-sizes.edit');
        Route::post('/update/{hatSize}', [HatSizeController::class, 'update'])->name('hat-sizes.update');
        Route::post('/delete/{hatSize}', [HatSizeController::class, 'destroy'])->name('hat-sizes.delete');
    });
    Route::prefix('locations')->group(function () {
        Route::get('/', [LocationController::class, 'index'])->name('locations.all');
        Route::get('/create', [LocationController::class, 'create'])->name('locations.create');
        Route::post('/create', [LocationController::class, 'store'])->name('locations.store');
        Route::get('/{location}', [LocationController::class, 'view'])->name('locations.view');
        Route::get('/update/{location}', [LocationController::class, 'edit'])->name('locations.edit');
        Route::post('/update/{location}', [LocationController::class, 'update'])->name('locations.update');
        Route::post('/delete/{location}', [LocationController::class, 'destroy'])->name('locations.delete');
    });
    Route::prefix('location-types')->group(function () {
        Route::get('/', [LocationTypeController::class, 'index'])->name('location-types.all');
        Route::get('/create', [LocationTypeController::class, 'create'])->name('location-types.create');
        Route::post('/create', [LocationTypeController::class, 'store'])->name('location-types.store');
        Route::get('/{locationType}', [LocationTypeController::class, 'view'])->name('location-types.view');
        Route::get('/update/{locationType}', [LocationTypeController::class, 'edit'])->name('location-types.edit');
        Route::post('/update/{locationType}', [LocationTypeController::class, 'update'])->name('location-types.update');
        Route::post('/delete/{locationType}', [LocationTypeController::class, 'destroy'])->name('location-types.delete');
    });
    Route::prefix('operators')->group(function () {
        Route::get('/', [OperatorController::class, 'index'])->name('operators.all');
        Route::get('/create', [OperatorController::class, 'create'])->name('operators.create');
        Route::post('/create', [OperatorController::class, 'store'])->name('operators.store');
        Route::get('/{operator}', [OperatorController::class, 'view'])->name('operators.view');
        Route::get('/update/{operator}', [OperatorController::class, 'edit'])->name('operators.edit');
        Route::post('/update/{operator}', [OperatorController::class, 'update'])->name('operators.update');
        Route::post('/delete/{operator}', [OperatorController::class, 'destroy'])->name('operators.delete');
    });
    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('payments.all');
        Route::get('/create', [PaymentController::class, 'create'])->name('payments.create');
        Route::post('/create', [PaymentController::class, 'store'])->name('payments.store');
        Route::get('/{payment}', [PaymentController::class, 'view'])->name('payments.view');
        Route::get('/update/{payment}', [PaymentController::class, 'edit'])->name('payments.edit');
        Route::post('/update/{payment}', [PaymentController::class, 'update'])->name('payments.update');
        Route::post('/delete/{payment}', [PaymentController::class, 'destroy'])->name('payments.delete');
    });
    Route::prefix('payment-methods')->group(function () {
        Route::get('/', [PaymentMethodController::class, 'index'])->name('payment-methods.all');
        Route::get('/create', [PaymentMethodController::class, 'create'])->name('payment-methods.create');
        Route::post('/create', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
        Route::get('/{paymentMethod}', [PaymentMethodController::class, 'view'])->name('payment-methods.view');
        Route::get('/update/{paymentMethod}', [PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
        Route::post('/update/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
        Route::post('/delete/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.delete');
    });
    Route::prefix('regions')->group(function () {
        Route::get('/', [RegionController::class, 'index'])->name('regions.all');
        Route::get('/create', [RegionController::class, 'create'])->name('regions.create');
        Route::post('/create', [RegionController::class, 'store'])->name('regions.store');
        Route::get('/{region}', [RegionController::class, 'view'])->name('regions.view');
        Route::get('/update/{region}', [RegionController::class, 'edit'])->name('regions.edit');
        Route::post('/update/{region}', [RegionController::class, 'update'])->name('regions.update');
        Route::post('/delete/{region}', [RegionController::class, 'destroy'])->name('regions.delete');
    });
    Route::prefix('room-types')->group(function () {
        Route::get('/', [RoomTypeController::class, 'index'])->name('room-types.all');
        Route::get('/create', [RoomTypeController::class, 'create'])->name('room-types.create');
        Route::post('/create', [RoomTypeController::class, 'store'])->name('room-types.store');
        Route::get('/{roomType}', [RoomTypeController::class, 'view'])->name('room-types.view');
        Route::get('/update/{roomType}', [RoomTypeController::class, 'edit'])->name('room-types.edit');
        Route::post('/update/{roomType}', [RoomTypeController::class, 'update'])->name('room-types.update');
        Route::post('/delete/{roomType}', [RoomTypeController::class, 'destroy'])->name('room-types.delete');
    });
    Route::prefix('t-shirt-sizes')->group(function () {
        Route::get('/', [TShirtSizeController::class, 'index'])->name('t-shirt-sizes.all');
        Route::get('/create', [TShirtSizeController::class, 'create'])->name('t-shirt-sizes.create');
        Route::post('/create', [TShirtSizeController::class, 'store'])->name('t-shirt-sizes.store');
        Route::get('/{tShirtSize}', [TShirtSizeController::class, 'view'])->name('t-shirt-sizes.view');
        Route::get('/update/{tShirtSize}', [TShirtSizeController::class, 'edit'])->name('t-shirt-sizes.edit');
        Route::post('/update/{tShirtSize}', [TShirtSizeController::class, 'update'])->name('t-shirt-sizes.update');
        Route::post('/delete/{tShirtSize}', [TShirtSizeController::class, 'destroy'])->name('t-shirt-sizes.delete');
    });
    Route::prefix('ticket-types')->group(function () {
        Route::get('/', [TicketTypeController::class, 'index'])->name('ticket-types.all');
        Route::get('/create', [TicketTypeController::class, 'create'])->name('ticket-types.create');
        Route::post('/create', [TicketTypeController::class, 'store'])->name('ticket-types.store');
        Route::get('/{ticketType}', [TicketTypeController::class, 'view'])->name('ticket-types.view');
        Route::get('/update/{ticketType}', [TicketTypeController::class, 'edit'])->name('ticket-types.edit');
        Route::post('/update/{ticketType}', [TicketTypeController::class, 'update'])->name('ticket-types.update');
        Route::post('/delete/{ticketType}', [TicketTypeController::class, 'destroy'])->name('ticket-types.delete');
    });
    Route::prefix('transport-types')->group(function () {
        Route::get('/', [TransportTypeController::class, 'index'])->name('transport-types.all');
        Route::get('/create', [TransportTypeController::class, 'create'])->name('transport-types.create');
        Route::post('/create', [TransportTypeController::class, 'store'])->name('transport-types.store');
        Route::get('/{transportType}', [TransportTypeController::class, 'view'])->name('transport-types.view');
        Route::get('/update/{transportType}', [TransportTypeController::class, 'edit'])->name('transport-types.edit');
        Route::post('/update/{transportType}', [TransportTypeController::class, 'update'])->name('transport-types.update');
        Route::post('/delete/{transportType}', [TransportTypeController::class, 'destroy'])->name('transport-types.delete');
    });
    Route::prefix('travel-classes')->group(function () {
        Route::get('/', [TravelClassController::class, 'index'])->name('travel-classes.all');
        Route::get('/create', [TravelClassController::class, 'create'])->name('travel-classes.create');
        Route::post('/create', [TravelClassController::class, 'store'])->name('travel-classes.store');
        Route::get('/{travelClass}', [TravelClassController::class, 'view'])->name('travel-classes.view');
        Route::get('/update/{travelClass}', [TravelClassController::class, 'edit'])->name('travel-classes.edit');
        Route::post('/update/{travelClass}', [TravelClassController::class, 'update'])->name('travel-classes.update');
        Route::post('/delete/{travelClass}', [TravelClassController::class, 'destroy'])->name('travel-classes.delete');
    });
});

Route::middleware('auth')->group(function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::get('/orders-users-components/{id}', [OrderCustomerController::class, 'customerComponents'])->name('customerComponents');
        Route::get('/tour-components/{id}', [TourController::class, 'tourComponents'])->name('tourComponents');
        Route::post('/tour-components/update', [TourController::class, 'tourComponentUpdate'])->name('tourComponentUpdate');
        Route::get('/orders-users-components/{id}', [OrderCustomerController::class, 'customerComponents'])->name('customerComponents');
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderSystemController::class, 'index'])->name("orders.all");
        Route::get('/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/create', [OrderController::class, 'store'])->name('orders.store');
        Route::prefix('{order}')->group(function () {
            Route::get('/', [OrderSystemController::class, 'show'])->name("orders.view");
            Route::get('/update/', [OrderController::class, 'edit'])->name('orders.edit');
            Route::post('/update/', [OrderController::class, 'update'])->name('orders.update');
            Route::post('/delete/', [OrderController::class, 'destroy'])->name('orders.delete');
            Route::prefix('adjustments')->group(function () {
                Route::get('/', [ManualAdjustmentController::class, 'index'])->name('manual-adjustments.all');
                Route::get('/create', [ManualAdjustmentController::class, 'create'])->name('manual-adjustments.create');
                Route::post('/create', [ManualAdjustmentController::class, 'store'])->name('manual-adjustments.store');
                Route::prefix('{manualAdjustment}')->group(function () {
                    Route::get('/', [ManualAdjustmentController::class, 'view'])->name('manual-adjustments.view');
                    Route::get('/update', [ManualAdjustmentController::class, 'edit'])->name('manual-adjustments.edit');
                    Route::post('/update', [ManualAdjustmentController::class, 'update'])->name('manual-adjustments.update');
                    Route::post('/delete', [ManualAdjustmentController::class, 'destroy'])->name('manual-adjustments.delete');
                });
            });
            Route::prefix('customer')->group(function () {
                Route::get('/', [OrdersCustomerController::class, 'index'])->name('orders-customers.all');
                Route::get('/create', [OrdersCustomerController::class, 'create'])->name('orders-customers.create');
                Route::post('/create', [OrdersCustomerController::class, 'store'])->name('orders-customers.store');
                Route::prefix('{orderCustomer}')->group(function () {
                    // This is staying in the OrderCustomerController, as moving it out breaks it somehow
                    Route::get('/', [OrderCustomerController::class, 'show'])->name("orders-customers.view");
                    Route::get('/update', [OrdersCustomerController::class, 'edit'])->name('orders-customers.edit');
                    Route::post('/update', [OrdersCustomerController::class, 'update'])->name('orders-customers.update');
                    Route::post('/delete', [OrdersCustomerController::class, 'destroy'])->name('orders-customers.delete');
                    Route::prefix('adjustment')->group(function () {
                        Route::get('/', [OrderCustomerAdjustmentController::class, 'index'])->name('order-customer-adjustments.all');
                        Route::get('/create', [OrderCustomerAdjustmentController::class, 'create'])->name('order-customer-adjustments.create');
                        Route::post('/create', [OrderCustomerAdjustmentController::class, 'store'])->name('order-customer-adjustments.store');
                        Route::prefix('{orderCustomerAdjustment}')->group(function () {
                            Route::get('/', [OrderCustomerAdjustmentController::class, 'view'])->name('order-customer-adjustments.view');
                            Route::get('/update', [OrderCustomerAdjustmentController::class, 'edit'])->name('order-customer-adjustments.edit');
                            Route::post('/update', [OrderCustomerAdjustmentController::class, 'update'])->name('order-customer-adjustments.update');
                            Route::post('/delete', [OrderCustomerAdjustmentController::class, 'destroy'])->name('order-customer-adjustments.delete');
                        });
                    });
                });
            });
        });
        Route::prefix('component')->group(function () {
            Route::post('accommodation/{id}/delete', [OrderComponentController::class, 'deleteAccommodation'])->name('orderAccommodationDelete');
            Route::post('activity/{id}/delete', [OrderComponentController::class, 'deleteActivity'])->name('orderActivityDelete');
            Route::post('flight/{id}/delete', [OrderComponentController::class, 'deleteFlight'])->name('orderFlightDelete');
            Route::post('transport/{id}/delete', [OrderComponentController::class, 'deleteTransport'])->name('orderTransportDelete');
        });
    });

    Route::prefix('accommodation')->group(function () {
        Route::get('/', [AccommodationController::class, 'index'])->name('accommodations.all');
        Route::get('/create', [AccommodationController::class, 'create'])->name('accommodations.create');
        Route::post('/create', [AccommodationController::class, 'store'])->name('accommodations.store');
        Route::get('/update/{accommodation}', [AccommodationController::class, 'edit'])->name('accommodations.edit');
        Route::post('/update/{accommodation}', [AccommodationController::class, 'update'])->name('accommodations.update');
        Route::post('/delete/{accommodation}', [AccommodationController::class, 'destroy'])->name('accommodations.delete');
        Route::get('/{accommodation}', [AccommodationController::class, 'view'])->name('accommodations.view');
        Route::prefix('inventory')->group(function () {
            Route::get('/{accommodation}/view/{accommodationInventory}', [AccommodationInventoryController::class, 'view'])->name('accommodation-inventories.view');
            Route::get('/{accommodation}/update/{accommodationInventory}', [AccommodationInventoryController::class, 'edit'])->name('accommodation-inventories.edit');
            Route::post('/{accommodation}/update/{accommodationInventory}', [AccommodationInventoryController::class, 'update'])->name('accommodation-inventories.update');
            Route::post('/{accommodation}/delete/{accommodationInventory}', [AccommodationInventoryController::class, 'destroy'])->name('accommodation-inventories.delete');
            Route::get('/{accommodation}/create', [AccommodationInventoryController::class, 'create'])->name('accommodation-inventories.create');
            Route::post('/{accommodation}/create', [AccommodationInventoryController::class, 'store'])->name('accommodation-inventories.store');
        });
    });

    Route::prefix('transports')->group(function () {
        Route::get('/', [TransportController::class, 'index'])->name('transports.all');
        Route::get('/create', [TransportController::class, 'create'])->name('transports.create');
        Route::post('/create', [TransportController::class, 'store'])->name('transports.store');
        Route::get('/update/{transport}', [TransportController::class, 'edit'])->name('transports.edit');
        Route::post('/update/{transport}', [TransportController::class, 'update'])->name('transports.update');
        Route::post('/delete/{transport}', [TransportController::class, 'destroy'])->name('transports.delete');
        Route::prefix('{transport}')->group(function () {
            Route::get('/', [TransportController::class, 'view'])->name('transports.view');
            Route::prefix('inventory')->group(function () {
                Route::get('/create', [TransportInventoryController::class, 'create'])->name('transport-inventories.create');
                Route::post('/create', [TransportInventoryController::class, 'store'])->name('transport-inventories.store');
                Route::get('/{transportInventory}', [TransportInventoryController::class, 'view'])->name('transport-inventories.view');
                Route::get('/update/{transportInventory}', [TransportInventoryController::class, 'edit'])->name('transport-inventories.edit');
                Route::post('/update/{transportInventory}', [TransportInventoryController::class, 'update'])->name('transport-inventories.update');
                Route::post('/delete/{transportInventory}', [TransportInventoryController::class, 'destroy'])->name('transport-inventories.delete');
            });
        });
    });

    Route::prefix('flights')->group(function () {
        Route::get('/', [FlightController::class, 'index'])->name('flights.all');
        Route::get('/create', [FlightController::class, 'create'])->name('flights.create');
        Route::post('/create', [FlightController::class, 'store'])->name('flights.store');
        Route::get('/update/{flight}', [FlightController::class, 'edit'])->name('flights.edit');
        Route::post('/update/{flight}', [FlightController::class, 'update'])->name('flights.update');
        Route::post('/delete/{flight}', [FlightController::class, 'destroy'])->name('flights.delete');
        Route::prefix('{flight}')->group(function () {
            Route::get('/', [FlightController::class, 'view'])->name('flights.view');
            Route::prefix('inventory')->group(function (){
                Route::get('/', [FlightInventoryController::class, 'index'])->name('flight-inventories.all');
                Route::get('/create', [FlightInventoryController::class, 'create'])->name('flight-inventories.create');
                Route::post('/create', [FlightInventoryController::class, 'store'])->name('flight-inventories.store');
                Route::get('/{flightInventory}', [FlightInventoryController::class, 'view'])->name('flight-inventories.view');
                Route::get('/update/{flightInventory}', [FlightInventoryController::class, 'edit'])->name('flight-inventories.edit');
                Route::post('/update/{flightInventory}', [FlightInventoryController::class, 'update'])->name('flight-inventories.update');
                Route::post('/delete/{flightInventory}', [FlightInventoryController::class, 'destroy'])->name('flight-inventories.delete');
            });
        });
    });

    Route::prefix('activities')->group(function () {
        Route::get('/', [ActivityController::class, 'index'])->name('activities.all');
        Route::get('/create', [ActivityController::class, 'create'])->name('activities.create');
        Route::post('/create', [ActivityController::class, 'store'])->name('activities.store');
        Route::get('/update/{activity}', [ActivityController::class, 'edit'])->name('activities.edit');
        Route::post('/update/{activity}', [ActivityController::class, 'update'])->name('activities.update');
        Route::post('/delete/{activity}', [ActivityController::class, 'destroy'])->name('activities.delete');
        Route::prefix('{activity}')->group(function () {
            Route::get('/', [ActivityController::class, 'view'])->name('activities.view');
            Route::prefix('inventory')->group(function () {
                Route::get('/create', [ActivityInventoryController::class, 'create'])->name('activity-inventories.create');
                Route::post('/create', [ActivityInventoryController::class, 'store'])->name('activity-inventories.store');
                Route::get('/{activityInventory}', [ActivityInventoryController::class, 'view'])->name('activity-inventories.view');
                Route::get('/update/{activityInventory}', [ActivityInventoryController::class, 'edit'])->name('activity-inventories.edit');
                Route::post('/update/{activityInventory}', [ActivityInventoryController::class, 'update'])->name('activity-inventories.update');
                Route::post('/delete/{activityInventory}', [ActivityInventoryController::class, 'destroy'])->name('activity-inventories.delete');
            });
        });
    });

    Route::prefix('tours')->group(function () {
        Route::get('/', [\App\Http\Controllers\Models\TourController::class, 'index'])->name('tours.all');
        Route::get('/create', [\App\Http\Controllers\Models\TourController::class, 'create'])->name('tours.create');
        Route::post('/create', [\App\Http\Controllers\Models\TourController::class, 'store'])->name('tours.store');
        Route::prefix('{tour}')->group(function () {
            Route::get('/', [\App\Http\Controllers\Models\TourController::class, 'view'])->name('tours.view');
            Route::get('/update', [\App\Http\Controllers\Models\TourController::class, 'edit'])->name('tours.edit');
            Route::post('/update', [\App\Http\Controllers\Models\TourController::class, 'update'])->name('tours.update');
            Route::post('/delete', [\App\Http\Controllers\Models\TourController::class, 'destroy'])->name('tours.delete');
            Route::get('/add', function (\App\Models\Tour $tour) { return view('pages.tour.components.add', ['tour' => $tour, ]); })->name('tours.add');
            Route::prefix('inventory')->group(function () {
                Route::prefix('accommodation')->group(function () {
                    Route::get('/', [AccommodationInventoryTourController::class, 'index'])->name('accommodation-inventory-tours.all');
                    Route::get('/create', [AccommodationInventoryTourController::class, 'create'])->name('accommodation-inventory-tours.create');
                    Route::post('/create', [AccommodationInventoryTourController::class, 'store'])->name('accommodation-inventory-tours.store');
                    Route::prefix('{accommodationInventoryTour}')->group(function () {
                        Route::get('/', [AccommodationInventoryTourController::class, 'view'])->name('accommodation-inventory-tours.view');
                        Route::get('/update', [AccommodationInventoryTourController::class, 'edit'])->name('accommodation-inventory-tours.edit');
                        Route::post('/update', [AccommodationInventoryTourController::class, 'update'])->name('accommodation-inventory-tours.update');
                        Route::post('/delete', [AccommodationInventoryTourController::class, 'destroy'])->name('accommodation-inventory-tours.delete');
                    });
                });
                Route::prefix('activity')->group(function () {
                    Route::get('/', [ActivityInventoryTourController::class, 'index'])->name('activity-inventory-tours.all');
                    Route::get('/create', [ActivityInventoryTourController::class, 'create'])->name('activity-inventory-tours.create');
                    Route::post('/create', [ActivityInventoryTourController::class, 'store'])->name('activity-inventory-tours.store');
                    Route::prefix('{activityInventoryTour}')->group(function () {
                        Route::get('/', [ActivityInventoryTourController::class, 'view'])->name('activity-inventory-tours.view');
                        Route::get('/update', [ActivityInventoryTourController::class, 'edit'])->name('activity-inventory-tours.edit');
                        Route::post('/update', [ActivityInventoryTourController::class, 'update'])->name('activity-inventory-tours.update');
                        Route::post('/delete', [ActivityInventoryTourController::class, 'destroy'])->name('activity-inventory-tours.delete');
                    });
                });
                Route::prefix('flight')->group(function () {
                    Route::get('/', [FlightInventoryTourController::class, 'index'])->name('flight-inventory-tours.all');
                    Route::get('/create', [FlightInventoryTourController::class, 'create'])->name('flight-inventory-tours.create');
                    Route::post('/create', [FlightInventoryTourController::class, 'store'])->name('flight-inventory-tours.store');
                    Route::prefix('{flightInventoryTour}')->group(function () {
                        Route::get('/', [FlightInventoryTourController::class, 'view'])->name('flight-inventory-tours.view');
                        Route::get('/update', [FlightInventoryTourController::class, 'edit'])->name('flight-inventory-tours.edit');
                        Route::post('/update', [FlightInventoryTourController::class, 'update'])->name('flight-inventory-tours.update');
                        Route::post('/delete', [FlightInventoryTourController::class, 'destroy'])->name('flight-inventory-tours.delete');
                    });
                });
                Route::prefix('transport')->group(function () {
                    Route::get('/', [TransportInventoryTourController::class, 'index'])->name('transport-inventory-tours.all');
                    Route::get('/create', [TransportInventoryTourController::class, 'create'])->name('transport-inventory-tours.create');
                    Route::post('/create', [TransportInventoryTourController::class, 'store'])->name('transport-inventory-tours.store');
                    Route::prefix('{transportInventoryTour}')->group(function () {
                        Route::get('/', [TransportInventoryTourController::class, 'view'])->name('transport-inventory-tours.view');
                        Route::get('/update', [TransportInventoryTourController::class, 'edit'])->name('transport-inventory-tours.edit');
                        Route::post('/update', [TransportInventoryTourController::class, 'update'])->name('transport-inventory-tours.update');
                        Route::post('/delete', [TransportInventoryTourController::class, 'destroy'])->name('transport-inventory-tours.delete');
                    });
                });
            });
        });
    });

    Route::get('/dash', function () { return view('pages.dash'); })->name('dash');

    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::post('/', [SettingsController::class, 'update'])->name('settings.update');
    });
});

Auth::routes();
