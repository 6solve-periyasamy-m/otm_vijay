<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingFormLoginController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\MailController;
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
use App\Http\Controllers\Models\LocationTypeController;
use App\Http\Controllers\Models\ManualAdjustmentController;
use App\Http\Controllers\Models\MerchandiseController;
use App\Http\Controllers\Models\OperatorController;
use App\Http\Controllers\Models\OrderController;
use App\Http\Controllers\Models\OrderCustomerAdjustmentController;
use App\Http\Controllers\Models\OrderCustomerModelController;
use App\Http\Controllers\Models\PaymentController;
use App\Http\Controllers\Models\PaymentInstallmentController;
use App\Http\Controllers\Models\PaymentMethodController;
use App\Http\Controllers\Models\RoomTypeController;
use App\Http\Controllers\Models\TicketTypeController;
use App\Http\Controllers\Models\TourCategoryController;
use App\Http\Controllers\Models\TransportController;
use App\Http\Controllers\Models\TransportInventoryController;
use App\Http\Controllers\Models\TransportInventoryTourController;
use App\Http\Controllers\Models\TransportTypeController;
use App\Http\Controllers\Models\TravelClassController;
use App\Http\Controllers\Models\TShirtSizeController;
use App\Http\Controllers\Models\UserController;
use App\Http\Controllers\OrderComponentController;
use App\Http\Controllers\OrderCustomerController;
use App\Http\Controllers\OrderSystemController;
use App\Http\Controllers\PaymentScheduleController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TourController;
use App\Models\Order;
use App\Models\Tour;
use App\Repository\OrderRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

    Route::get('/{url}', [BookingController::class, 'tourBookingForm'])->name('booking.url');

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

Route::middleware('auth')->prefix('admin')->group(function () {

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderSystemController::class, 'index'])->name("orders.all")->middleware('bouncer:Order,read');
        Route::get('/create', [OrderController::class, 'create'])->name('orders.create')->middleware('bouncer:Order,create');
        Route::post('/create', [OrderController::class, 'store'])->name('orders.store')->middleware('bouncer:Order,create');

        Route::prefix('{order}')->group(function () {
            Route::get('/', [OrderSystemController::class, 'show'])->name("orders.view")->middleware('bouncer:Order,read');
            Route::get('/update/', [OrderController::class, 'edit'])->name('orders.edit')->middleware('bouncer:Order,update');
            Route::post('/update/', [OrderController::class, 'update'])->name('orders.update')->middleware('bouncer:Order,update');
            Route::post('/delete/', [OrderController::class, 'destroy'])->name('orders.delete')->middleware('bouncer:Order,delete');
            Route::get('/invoice', function (Order $order) {
                return view('pdf.invoices.columns', OrderRepository::getInvoiceDetails($order));
            })->name('orders.invoice.latest')->middleware('bouncer:Order,read');

            Route::prefix('adjustments')->group(function () {
                Route::get('/', [ManualAdjustmentController::class, 'index'])->name('manual-adjustments.all')->middleware('bouncer:ManualAdjustment,read');
                Route::get('/create', [ManualAdjustmentController::class, 'create'])->name('manual-adjustments.create')->middleware('bouncer:ManualAdjustment,create');
                Route::post('/create', [ManualAdjustmentController::class, 'store'])->name('manual-adjustments.store')->middleware('bouncer:ManualAdjustment,create');

                Route::prefix('{manualAdjustment}')->group(function () {
                    Route::get('/', [ManualAdjustmentController::class, 'view'])->name('manual-adjustments.view')->middleware('bouncer:ManualAdjustment,read');
                    Route::get('/update', [ManualAdjustmentController::class, 'edit'])->name('manual-adjustments.edit')->middleware('bouncer:ManualAdjustment,update');
                    Route::post('/update', [ManualAdjustmentController::class, 'update'])->name('manual-adjustments.update')->middleware('bouncer:ManualAdjustment,update');
                    Route::post('/delete', [ManualAdjustmentController::class, 'destroy'])->name('manual-adjustments.delete')->middleware('bouncer:ManualAdjustment,delete');
                });
            });

            Route::prefix('customer')->group(function () {
                Route::get('/', [OrderCustomerModelController::class, 'index'])->name('order-customers.all')->middleware('bouncer:OrderCustomer,read');
                Route::get('/create', [OrderCustomerModelController::class, 'create'])->name('order-customers.create')->middleware('bouncer:OrderCustomer,create');
                Route::post('/create', [OrderCustomerModelController::class, 'store'])->name('order-customers.store')->middleware('bouncer:OrderCustomer,create');

                Route::prefix('{orderCustomer}')->group(function () {
                    // This is staying in the OrderCustomerController, as moving it out breaks it somehow
                    Route::get('/', [OrderCustomerController::class, 'show'])->name("order-customers.view")->middleware('bouncer:OrderCustomer,read');
                    Route::get('/update', [OrderCustomerModelController::class, 'edit'])->name('order-customers.edit')->middleware('bouncer:OrderCustomer,update');
                    Route::post('/update', [OrderCustomerModelController::class, 'update'])->name('order-customers.update')->middleware('bouncer:OrderCustomer,update');
                    Route::post('/delete', [OrderCustomerModelController::class, 'destroy'])->name('order-customers.delete')->middleware('bouncer:OrderCustomer,delete');

                    Route::prefix('adjustment')->group(function () {
                        Route::get('/', [OrderCustomerAdjustmentController::class, 'index'])->name('order-customer-adjustments.all')->middleware('bouncer:OrderCustomerAdjustment,read');
                        Route::get('/create', [OrderCustomerAdjustmentController::class, 'create'])->name('order-customer-adjustments.create')->middleware('bouncer:OrderCustomerAdjustment,create');
                        Route::post('/create', [OrderCustomerAdjustmentController::class, 'store'])->name('order-customer-adjustments.store')->middleware('bouncer:OrderCustomerAdjustment,create');

                        Route::prefix('{orderCustomerAdjustment}')->group(function () {
                            Route::get('/', [OrderCustomerAdjustmentController::class, 'view'])->name('order-customer-adjustments.view')->middleware('bouncer:OrderCustomerAdjustment,read');
                            Route::get('/update', [OrderCustomerAdjustmentController::class, 'edit'])->name('order-customer-adjustments.edit')->middleware('bouncer:OrderCustomerAdjustment,update');
                            Route::post('/update', [OrderCustomerAdjustmentController::class, 'update'])->name('order-customer-adjustments.update')->middleware('bouncer:OrderCustomerAdjustment,update');
                            Route::post('/delete', [OrderCustomerAdjustmentController::class, 'destroy'])->name('order-customer-adjustments.delete')->middleware('bouncer:OrderCustomerAdjustment,delete');
                        });
                    });
                });
            });

            Route::prefix('payments')->group(function () {
                Route::get('/', [PaymentController::class, 'index'])->name('payments.all')->middleware('bouncer:Payment,read');
                Route::get('/create', [PaymentController::class, 'create'])->name('payments.create')->middleware('bouncer:Payment,create');
                Route::post('/create', [PaymentController::class, 'store'])->name('payments.store')->middleware('bouncer:Payment,create');

                Route::prefix('{payment}')->group(function () {
                    Route::get('/', [PaymentController::class, 'view'])->name('payments.view')->middleware('bouncer:Payment,read');
                    Route::get('/update', [PaymentController::class, 'edit'])->name('payments.edit')->middleware('bouncer:Payment,update');
                    Route::post('/update', [PaymentController::class, 'update'])->name('payments.update')->middleware('bouncer:Payment,update');
                    Route::post('/delete', [PaymentController::class, 'destroy'])->name('payments.delete')->middleware('bouncer:Payment,delete');
                });

                Route::prefix('payment-methods')->group(function () {
                    Route::get('/', [PaymentMethodController::class, 'index'])->name('payment-methods.all')->middleware('bouncer:PaymentMethod,read');
                    Route::get('/create', [PaymentMethodController::class, 'create'])->name('payment-methods.create')->middleware('bouncer:PaymentMethod,create');
                    Route::post('/create', [PaymentMethodController::class, 'store'])->name('payment-methods.store')->middleware('bouncer:PaymentMethod,create');
                    Route::prefix('{paymentMethod}')->group(function () {
                        Route::get('/', [PaymentMethodController::class, 'view'])->name('payment-methods.view')->middleware('bouncer:PaymentMethod,read');
                        Route::get('/update', [PaymentMethodController::class, 'edit'])->name('payment-methods.edit')->middleware('bouncer:PaymentMethod,update');
                        Route::post('/update', [PaymentMethodController::class, 'update'])->name('payment-methods.update')->middleware('bouncer:PaymentMethod,update');
                        Route::post('/delete', [PaymentMethodController::class, 'destroy'])->name('payment-methods.delete')->middleware('bouncer:PaymentMethod,delete');
                    });
                });
            });
        });

        Route::prefix('component')->group(function () {
            Route::post('accommodation/{id}/delete', [OrderComponentController::class, 'deleteAccommodation'])->name('orderAccommodationDelete')->middleware('bouncer:OrderCustomer,update');
            Route::post('activity/{id}/delete', [OrderComponentController::class, 'deleteActivity'])->name('orderActivityDelete')->middleware('bouncer:OrderCustomer,update');
            Route::post('flight/{id}/delete', [OrderComponentController::class, 'deleteFlight'])->name('orderFlightDelete')->middleware('bouncer:OrderCustomer,update');
            Route::post('transport/{id}/delete', [OrderComponentController::class, 'deleteTransport'])->name('orderTransportDelete')->middleware('bouncer:OrderCustomer,update');
            Route::post('merchandise/{id}/delete', [OrderComponentController::class, 'deleteMerchandise'])->name('orderMerchandiseDelete')->middleware('bouncer:OrderCustomer,update');
        });
    });

    Route::prefix('accommodation')->group(function () {
        Route::get('/', [AccommodationController::class, 'index'])->name('accommodations.all')->middleware('bouncer:Accommodation,read');
        Route::get('/create', [AccommodationController::class, 'create'])->name('accommodations.create')->middleware('bouncer:Accommodation,read');
        Route::post('/create', [AccommodationController::class, 'store'])->name('accommodations.store')->middleware('bouncer:Accommodation,read');

        Route::prefix('{accommodation}')->group(function () {
            Route::get('/', [AccommodationController::class, 'view'])->name('accommodations.view')->middleware('bouncer:Accommodation,read');
            Route::get('/update', [AccommodationController::class, 'edit'])->name('accommodations.edit')->middleware('bouncer:Accommodation,update');
            Route::post('/update', [AccommodationController::class, 'update'])->name('accommodations.update')->middleware('bouncer:Accommodation,update');
            Route::post('/delete', [AccommodationController::class, 'destroy'])->name('accommodations.delete')->middleware('bouncer:Accommodation,delete');

            Route::prefix('inventory')->group(function () {
                Route::get('/create', [AccommodationInventoryController::class, 'create'])->name('accommodation-inventories.create')->middleware('bouncer:AccommodationInventory,create');
                Route::post('/create', [AccommodationInventoryController::class, 'store'])->name('accommodation-inventories.store')->middleware('bouncer:AccommodationInventory,create');
                Route::prefix('{accommodationInventory}')->group(function () {
                    Route::get('/view', [AccommodationInventoryController::class, 'view'])->name('accommodation-inventories.view')->middleware('bouncer:AccommodationInventory,read');
                    Route::get('/update', [AccommodationInventoryController::class, 'edit'])->name('accommodation-inventories.edit')->middleware('bouncer:AccommodationInventory,update');
                    Route::post('/update', [AccommodationInventoryController::class, 'update'])->name('accommodation-inventories.update')->middleware('bouncer:AccommodationInventory,update');
                    Route::post('/delete', [AccommodationInventoryController::class, 'destroy'])->name('accommodation-inventories.delete')->middleware('bouncer:AccommodationInventory,delete');
                    Route::get('/duplicate', [AccommodationInventoryController::class, 'duplicate'])->name('accommodation-inventories.duplicate')->middleware('bouncer:AccommodationInventory,create');
                });

            });
        });
        Route::prefix('room-types')->group(function () {
            Route::get('/', [RoomTypeController::class, 'index'])->name('room-types.all')->middleware('bouncer:RoomType,read');
            Route::get('/create', [RoomTypeController::class, 'create'])->name('room-types.create')->middleware('bouncer:RoomType,create');
            Route::post('/create', [RoomTypeController::class, 'store'])->name('room-types.store')->middleware('bouncer:RoomType,create');
            Route::prefix('{roomType}')->group(function () {
                Route::get('/', [RoomTypeController::class, 'view'])->name('room-types.view')->middleware('bouncer:RoomType,read');
                Route::get('/update', [RoomTypeController::class, 'edit'])->name('room-types.edit')->middleware('bouncer:RoomType,update');
                Route::post('/update', [RoomTypeController::class, 'update'])->name('room-types.update')->middleware('bouncer:RoomType,update');
                Route::post('/delete', [RoomTypeController::class, 'destroy'])->name('room-types.delete')->middleware('bouncer:RoomType,delete');
            });
        });
        Route::prefix('board-types')->group(function () {
            Route::get('/', [BoardTypeController::class, 'index'])->name('board-types.all')->middleware('bouncer:BoardType,read');
            Route::get('/create', [BoardTypeController::class, 'create'])->name('board-types.create')->middleware('bouncer:BoardType,create');
            Route::post('/create', [BoardTypeController::class, 'store'])->name('board-types.store')->middleware('bouncer:BoardType,create');
            Route::prefix('{boardType}')->group(function () {
                Route::get('/', [BoardTypeController::class, 'view'])->name('board-types.view')->middleware('bouncer:BoardType,read');
                Route::get('/update', [BoardTypeController::class, 'edit'])->name('board-types.edit')->middleware('bouncer:BoardType,update');
                Route::post('/update', [BoardTypeController::class, 'update'])->name('board-types.update')->middleware('bouncer:BoardType,update');
                Route::post('/delete', [BoardTypeController::class, 'destroy'])->name('board-types.delete')->middleware('bouncer:BoardType,delete');
            });
        });
    });

    Route::prefix('transports')->group(function () {
        Route::get('/', [TransportController::class, 'index'])->name('transports.all')->middleware('bouncer:Transport,read');
        Route::get('/create', [TransportController::class, 'create'])->name('transports.create')->middleware('bouncer:Transport,create');
        Route::post('/create', [TransportController::class, 'store'])->name('transports.store')->middleware('bouncer:Transport,create');
        Route::prefix('{transport}')->group(function () {
            Route::get('/', [TransportController::class, 'view'])->name('transports.view')->middleware('bouncer:Transport,read');
            Route::get('/update', [TransportController::class, 'edit'])->name('transports.edit')->middleware('bouncer:Transport,update');
            Route::post('/update', [TransportController::class, 'update'])->name('transports.update')->middleware('bouncer:Transport,update');
            Route::post('/delete', [TransportController::class, 'destroy'])->name('transports.delete')->middleware('bouncer:Transport,delete');
            Route::get('/replicate', [TransportController::class, 'createReturn'])->name('transports.return');
            Route::prefix('inventory')->group(function () {
                Route::get('/create', [TransportInventoryController::class, 'create'])->name('transport-inventories.create')->middleware('bouncer:TransportInventory,create');
                Route::post('/create', [TransportInventoryController::class, 'store'])->name('transport-inventories.store')->middleware('bouncer:TransportInventory,create');
                Route::prefix('{transportInventory}')->group(function () {
                    Route::get('/', [TransportInventoryController::class, 'view'])->name('transport-inventories.view')->middleware('bouncer:TransportInventory,read');
                    Route::get('/update', [TransportInventoryController::class, 'edit'])->name('transport-inventories.edit')->middleware('bouncer:TransportInventory,update');
                    Route::post('/update', [TransportInventoryController::class, 'update'])->name('transport-inventories.update')->middleware('bouncer:TransportInventory,update');
                    Route::post('/delete', [TransportInventoryController::class, 'destroy'])->name('transport-inventories.delete')->middleware('bouncer:TransportInventory,delete');
                    Route::get('/duplicate', [TransportInventoryController::class, 'duplicate'])->name('transport-inventories.duplicate')->middleware('bouncer:TransportInventory,create');
                });
            });
        });
        Route::prefix('operators')->group(function () {
            Route::get('/', [OperatorController::class, 'index'])->name('operators.all')->middleware('bouncer:Operator,read');
            Route::get('/create', [OperatorController::class, 'create'])->name('operators.create')->middleware('bouncer:Operator,create');
            Route::post('/create', [OperatorController::class, 'store'])->name('operators.store')->middleware('bouncer:Operator,create');
            Route::prefix('{operator}')->group(function () {
                Route::get('/', [OperatorController::class, 'view'])->name('operators.view')->middleware('bouncer:Operator,read');
                Route::get('/update', [OperatorController::class, 'edit'])->name('operators.edit')->middleware('bouncer:Operator,update');
                Route::post('/update', [OperatorController::class, 'update'])->name('operators.update')->middleware('bouncer:Operator,update');
                Route::post('/delete', [OperatorController::class, 'destroy'])->name('operators.delete')->middleware('bouncer:Operator,delete');

            });
        });
        Route::prefix('transport-types')->group(function () {
            Route::get('/', [TransportTypeController::class, 'index'])->name('transport-types.all')->middleware('bouncer:Operator,read');
            Route::get('/create', [TransportTypeController::class, 'create'])->name('transport-types.create')->middleware('bouncer:Operator,create');
            Route::post('/create', [TransportTypeController::class, 'store'])->name('transport-types.store')->middleware('bouncer:Operator,create');
            Route::prefix('{transportType}')->group(function () {
                Route::get('/', [TransportTypeController::class, 'view'])->name('transport-types.view')->middleware('bouncer:TransportType,read');
                Route::get('/update', [TransportTypeController::class, 'edit'])->name('transport-types.edit')->middleware('bouncer:TransportType,update');
                Route::post('/update', [TransportTypeController::class, 'update'])->name('transport-types.update')->middleware('bouncer:TransportType,update');
                Route::post('/delete', [TransportTypeController::class, 'destroy'])->name('transport-types.delete')->middleware('bouncer:TransportType,delete');
            });
        });
    });

    Route::prefix('travel-classes')->group(function () {
        Route::get('/', [TravelClassController::class, 'index'])->name('travel-classes.all')->middleware('bouncer:TravelClass,read');
        Route::get('/create', [TravelClassController::class, 'create'])->name('travel-classes.create')->middleware('bouncer:TravelClass,create');
        Route::post('/create', [TravelClassController::class, 'store'])->name('travel-classes.store')->middleware('bouncer:TravelClass,create');
        Route::prefix('{travelClass}')->group(function () {
            Route::get('/', [TravelClassController::class, 'view'])->name('travel-classes.view')->middleware('bouncer:TravelClass,read');
            Route::get('/update', [TravelClassController::class, 'edit'])->name('travel-classes.edit')->middleware('bouncer:TravelClass,update');
            Route::post('/update', [TravelClassController::class, 'update'])->name('travel-classes.update')->middleware('bouncer:TravelClass,update');
            Route::post('/delete', [TravelClassController::class, 'destroy'])->name('travel-classes.delete')->middleware('bouncer:TravelClass,delete');
        });
    });

    Route::prefix('flights')->group(function () {
        Route::get('/', [FlightController::class, 'index'])->name('flights.all')->middleware('bouncer:Flight,read');
        Route::get('/create', [FlightController::class, 'create'])->name('flights.create')->middleware('bouncer:Flight,create');
        Route::post('/create', [FlightController::class, 'store'])->name('flights.store')->middleware('bouncer:Flight,create');
        Route::prefix('{flight}')->group(function () {
            Route::get('/', [FlightController::class, 'view'])->name('flights.view')->middleware('bouncer:Flight,read');
            Route::get('/update', [FlightController::class, 'edit'])->name('flights.edit')->middleware('bouncer:Flight,update');
            Route::post('/update', [FlightController::class, 'update'])->name('flights.update')->middleware('bouncer:Flight,update');
            Route::post('/delete', [FlightController::class, 'destroy'])->name('flights.delete')->middleware('bouncer:Flight,delete');
            Route::get('/replicate', [FlightController::class, 'createReturn'])->name('flights.return');
            Route::prefix('inventory')->group(function () {
                Route::get('/', [FlightInventoryController::class, 'index'])->name('flight-inventories.all')->middleware('bouncer:FlightInventory,read');
                Route::get('/create', [FlightInventoryController::class, 'create'])->name('flight-inventories.create')->middleware('bouncer:FlightInventory,create');
                Route::post('/create', [FlightInventoryController::class, 'store'])->name('flight-inventories.store')->middleware('bouncer:FlightInventory,create');
                Route::prefix('{flightInventory}')->group(function () {
                    Route::get('/', [FlightInventoryController::class, 'view'])->name('flight-inventories.view')->middleware('bouncer:FlightInventory,read');
                    Route::get('/update', [FlightInventoryController::class, 'edit'])->name('flight-inventories.edit')->middleware('bouncer:FlightInventory,update');
                    Route::post('/update', [FlightInventoryController::class, 'update'])->name('flight-inventories.update')->middleware('bouncer:FlightInventory,update');
                    Route::post('/delete', [FlightInventoryController::class, 'destroy'])->name('flight-inventories.delete')->middleware('bouncer:FlightInventory,delete');
                    Route::get('/duplicate', [FlightInventoryController::class, 'duplicate'])->name('flight-inventories.duplicate')->middleware('bouncer:FlightInventory,create');
                });
            });
        });

        Route::prefix('airlines')->group(function () {
            Route::get('/', [AirlineController::class, 'index'])->name('airlines.all')->middleware('bouncer:Airline,read');
            Route::get('/create', [AirlineController::class, 'create'])->name('airlines.create')->middleware('bouncer:Airline,create');
            Route::post('/create', [AirlineController::class, 'store'])->name('airlines.store')->middleware('bouncer:Airline,create');
            Route::prefix('{airline}')->group(function () {
                Route::get('/', [AirlineController::class, 'view'])->name('airlines.view')->middleware('bouncer:Airline,read');
                Route::get('/update', [AirlineController::class, 'edit'])->name('airlines.edit')->middleware('bouncer:Airline,update');
                Route::post('/update', [AirlineController::class, 'update'])->name('airlines.update')->middleware('bouncer:Airline,update');
                Route::post('/delete', [AirlineController::class, 'destroy'])->name('airlines.delete')->middleware('bouncer:Airline,delete');
            });
        });

        Route::prefix('airports')->group(function () {
            Route::get('/', [AirportController::class, 'index'])->name('airports.all')->middleware('bouncer:Airport,read');
            Route::get('/create', [AirportController::class, 'create'])->name('airports.create')->middleware('bouncer:Airport,create');
            Route::post('/create', [AirportController::class, 'store'])->name('airports.store')->middleware('bouncer:Airport,create');
            Route::prefix('{airport}')->group(function () {
                Route::get('/', [AirportController::class, 'view'])->name('airports.view')->middleware('bouncer:Airport,read');
                Route::get('/update', [AirportController::class, 'edit'])->name('airports.edit')->middleware('bouncer:Airport,update');
                Route::post('/update', [AirportController::class, 'update'])->name('airports.update')->middleware('bouncer:Airport,update');
                Route::post('/delete', [AirportController::class, 'destroy'])->name('airports.delete')->middleware('bouncer:Airport,delete');
            });
        });
    });

    Route::prefix('activities')->group(function () {
        Route::get('/', [ActivityController::class, 'index'])->name('activities.all')->middleware('bouncer:Activity,read');
        Route::get('/create', [ActivityController::class, 'create'])->name('activities.create')->middleware('bouncer:Activity,create');
        Route::post('/create', [ActivityController::class, 'store'])->name('activities.store')->middleware('bouncer:Activity,create');
        Route::prefix('{activity}')->group(function () {
            Route::get('/', [ActivityController::class, 'view'])->name('activities.view')->middleware('bouncer:Activity,read');
            Route::get('/update', [ActivityController::class, 'edit'])->name('activities.edit')->middleware('bouncer:Activity,update');
            Route::post('/update', [ActivityController::class, 'update'])->name('activities.update')->middleware('bouncer:Activity,update');
            Route::post('/delete', [ActivityController::class, 'destroy'])->name('activities.delete')->middleware('bouncer:Activity,delete');
            Route::prefix('inventory')->group(function () {
                Route::get('/create', [ActivityInventoryController::class, 'create'])->name('activity-inventories.create')->middleware('bouncer:ActivityInventory,create');
                Route::post('/create', [ActivityInventoryController::class, 'store'])->name('activity-inventories.store')->middleware('bouncer:ActivityInventory,create');
                Route::prefix('{activityInventory}')->group(function () {
                    Route::get('/', [ActivityInventoryController::class, 'view'])->name('activity-inventories.view')->middleware('bouncer:ActivityInventory,read');
                    Route::get('/update', [ActivityInventoryController::class, 'edit'])->name('activity-inventories.edit')->middleware('bouncer:ActivityInventory,update');
                    Route::post('/update', [ActivityInventoryController::class, 'update'])->name('activity-inventories.update')->middleware('bouncer:ActivityInventory,update');
                    Route::post('/delete', [ActivityInventoryController::class, 'destroy'])->name('activity-inventories.delete')->middleware('bouncer:ActivityInventory,delete');
                    Route::get('/duplicate', [ActivityInventoryController::class, 'duplicate'])->name('activity-inventories.duplicate')->middleware('bouncer:ActivityInventory,create');
                });
            });
        });
        Route::prefix('activity-types')->group(function () {
            Route::get('/', [ActivityTypeController::class, 'index'])->name('activity-types.all')->middleware('bouncer:ActivityType,read');
            Route::get('/create', [ActivityTypeController::class, 'create'])->name('activity-types.create')->middleware('bouncer:ActivityType,create');
            Route::post('/create', [ActivityTypeController::class, 'store'])->name('activity-types.store')->middleware('bouncer:ActivityType,create');
            Route::prefix('{activityType}')->group(function () {
                Route::get('/', [ActivityTypeController::class, 'view'])->name('activity-types.view')->middleware('bouncer:ActivityType,read');
                Route::get('/update', [ActivityTypeController::class, 'edit'])->name('activity-types.edit')->middleware('bouncer:ActivityType,update');
                Route::post('/update', [ActivityTypeController::class, 'update'])->name('activity-types.update')->middleware('bouncer:ActivityType,update');
                Route::post('/delete', [ActivityTypeController::class, 'destroy'])->name('activity-types.delete')->middleware('bouncer:ActivityType,delete');
            });
        });
        Route::prefix('ticket-types')->group(function () {
            Route::get('/', [TicketTypeController::class, 'index'])->name('ticket-types.all')->middleware('bouncer:TicketType,read');
            Route::get('/create', [TicketTypeController::class, 'create'])->name('ticket-types.create')->middleware('bouncer:TicketType,create');
            Route::post('/create', [TicketTypeController::class, 'store'])->name('ticket-types.store')->middleware('bouncer:TicketType,create');
            Route::prefix('{ticketType}')->group(function () {
                Route::get('/', [TicketTypeController::class, 'view'])->name('ticket-types.view')->middleware('bouncer:TicketType,read');
                Route::get('/update', [TicketTypeController::class, 'edit'])->name('ticket-types.edit')->middleware('bouncer:TicketType,update');
                Route::post('/update', [TicketTypeController::class, 'update'])->name('ticket-types.update')->middleware('bouncer:TicketType,update');
                Route::post('/delete', [TicketTypeController::class, 'destroy'])->name('ticket-types.delete')->middleware('bouncer:TicketType,delete');
            });
        });
    });

    Route::prefix('tours')->group(function () {
        Route::get('/', [\App\Http\Controllers\Models\TourController::class, 'index'])->name('tours.all')->middleware('bouncer:Tour,read');
        Route::get('/create', [\App\Http\Controllers\Models\TourController::class, 'create'])->name('tours.create')->middleware('bouncer:Tour,create');
        Route::post('/create', [\App\Http\Controllers\Models\TourController::class, 'store'])->name('tours.store')->middleware('bouncer:Tour,create');
        Route::prefix('{tour}')->group(function () {
            Route::get('/', [\App\Http\Controllers\Models\TourController::class, 'view'])->name('tours.view')->middleware('bouncer:Tour,read');
            Route::get('/update', [\App\Http\Controllers\Models\TourController::class, 'edit'])->name('tours.edit')->middleware('bouncer:Tour,update');
            Route::post('/update', [\App\Http\Controllers\Models\TourController::class, 'update'])->name('tours.update')->middleware('bouncer:Tour,update');
            Route::post('/delete', [\App\Http\Controllers\Models\TourController::class, 'destroy'])->name('tours.delete')->middleware('bouncer:Tour,delete');
            Route::get('/add', function (Tour $tour) {
                return view('pages.tour.components.add', ['tour' => $tour,]);
            })->name('tours.add')->middleware('bouncer:Tour,update');
            Route::prefix('inventory')->group(function () {
                Route::prefix('accommodation')->group(function () {
                    Route::get('/create', [AccommodationInventoryTourController::class, 'create'])->name('accommodation-inventory-tours.create')->middleware('bouncer:AccommodationInventoryTour,create');
                    Route::post('/create', [AccommodationInventoryTourController::class, 'store'])->name('accommodation-inventory-tours.store')->middleware('bouncer:AccommodationInventoryTour,create');
                    Route::prefix('{accommodationInventoryTour}')->group(function () {
                        Route::get('/', [AccommodationInventoryTourController::class, 'view'])->name('accommodation-inventory-tours.view')->middleware('bouncer:AccommodationInventoryTour,read');
                        Route::get('/update', [AccommodationInventoryTourController::class, 'edit'])->name('accommodation-inventory-tours.edit')->middleware('bouncer:AccommodationInventoryTour,update');
                        Route::post('/update', [AccommodationInventoryTourController::class, 'update'])->name('accommodation-inventory-tours.update')->middleware('bouncer:AccommodationInventoryTour,update');
                        Route::post('/delete', [AccommodationInventoryTourController::class, 'destroy'])->name('accommodation-inventory-tours.delete')->middleware('bouncer:AccommodationInventoryTour,delete');
                    });
                });
                Route::prefix('activity')->group(function () {
                    Route::get('/create', [ActivityInventoryTourController::class, 'create'])->name('activity-inventory-tours.create')->middleware('bouncer:ActivityInventoryTour,create');
                    Route::post('/create', [ActivityInventoryTourController::class, 'store'])->name('activity-inventory-tours.store')->middleware('bouncer:ActivityInventoryTour,create');
                    Route::prefix('{activityInventoryTour}')->group(function () {
                        Route::get('/', [ActivityInventoryTourController::class, 'view'])->name('activity-inventory-tours.view')->middleware('bouncer:ActivityInventoryTour,read');
                        Route::get('/update', [ActivityInventoryTourController::class, 'edit'])->name('activity-inventory-tours.edit')->middleware('bouncer:ActivityInventoryTour,update');
                        Route::post('/update', [ActivityInventoryTourController::class, 'update'])->name('activity-inventory-tours.update')->middleware('bouncer:ActivityInventoryTour,update');
                        Route::post('/delete', [ActivityInventoryTourController::class, 'destroy'])->name('activity-inventory-tours.delete')->middleware('bouncer:ActivityInventoryTour,delete');
                    });
                });
                Route::prefix('flight')->group(function () {
                    Route::get('/create', [FlightInventoryTourController::class, 'create'])->name('flight-inventory-tours.create')->middleware('bouncer:FlightInventoryTour,create');
                    Route::post('/create', [FlightInventoryTourController::class, 'store'])->name('flight-inventory-tours.store')->middleware('bouncer:FlightInventoryTour,create');
                    Route::prefix('{flightInventoryTour}')->group(function () {
                        Route::get('/', [FlightInventoryTourController::class, 'view'])->name('flight-inventory-tours.view')->middleware('bouncer:FlightInventoryTour,read');
                        Route::get('/update', [FlightInventoryTourController::class, 'edit'])->name('flight-inventory-tours.edit')->middleware('bouncer:FlightInventoryTour,update');
                        Route::post('/update', [FlightInventoryTourController::class, 'update'])->name('flight-inventory-tours.update')->middleware('bouncer:FlightInventoryTour,update');
                        Route::post('/delete', [FlightInventoryTourController::class, 'destroy'])->name('flight-inventory-tours.delete')->middleware('bouncer:FlightInventoryTour,delete');
                    });
                });
                Route::prefix('transport')->group(function () {
                    Route::get('/create', [TransportInventoryTourController::class, 'create'])->name('transport-inventory-tours.create')->middleware('bouncer:TransportInventoryTour,create');
                    Route::post('/create', [TransportInventoryTourController::class, 'store'])->name('transport-inventory-tours.store')->middleware('bouncer:TransportInventoryTour,create');
                    Route::prefix('{transportInventoryTour}')->group(function () {
                        Route::get('/', [TransportInventoryTourController::class, 'view'])->name('transport-inventory-tours.view')->middleware('bouncer:TransportInventoryTour,read');
                        Route::get('/update', [TransportInventoryTourController::class, 'edit'])->name('transport-inventory-tours.edit')->middleware('bouncer:TransportInventoryTour,update');
                        Route::post('/update', [TransportInventoryTourController::class, 'update'])->name('transport-inventory-tours.update')->middleware('bouncer:TransportInventoryTour,update');
                        Route::post('/delete', [TransportInventoryTourController::class, 'destroy'])->name('transport-inventory-tours.delete')->middleware('bouncer:TransportInventoryTour,delete=');
                    });
                });
            });
            Route::prefix('payment-installments')->group(function () {
                Route::get('/create', [PaymentInstallmentController::class, 'create'])->name('payment-installments.create')->middleware('bouncer:Tour,update');
                Route::post('/create', [PaymentInstallmentController::class, 'store'])->name('payment-installments.store')->middleware('bouncer:Tour,update');
                Route::prefix('{paymentInstallment}')->group(function () {
                    Route::get('/', [PaymentInstallmentController::class, 'view'])->name('payment-installments.view')->middleware('bouncer:Tour,read');
                    Route::get('/update', [PaymentInstallmentController::class, 'edit'])->name('payment-installments.edit')->middleware('bouncer:Tour,update');
                    Route::post('/update', [PaymentInstallmentController::class, 'update'])->name('payment-installments.update')->middleware('bouncer:Tour,update');
                    Route::post('/delete', [PaymentInstallmentController::class, 'destroy'])->name('payment-installments.delete')->middleware('bouncer:Tour,update');
                });
            });
            Route::prefix('merchandise')->group(function () {
                Route::get('/', [MerchandiseController::class, 'index'])->name('merchandise.all');
                Route::get('/create', [MerchandiseController::class, 'create'])->name('merchandise.create');
                Route::post('/create', [MerchandiseController::class, 'store'])->name('merchandise.store');
                Route::prefix('{merchandise}')->group(function () {
                    Route::get('/', [MerchandiseController::class, 'view'])->name('merchandise.view');
                    Route::get('/update', [MerchandiseController::class, 'edit'])->name('merchandise.edit');
                    Route::post('/update', [MerchandiseController::class, 'update'])->name('merchandise.update');
                    Route::post('/delete', [MerchandiseController::class, 'destroy'])->name('merchandise.delete');
                });
            });
        });
        Route::prefix('tour-categories')->group(function () {
            Route::get('/', [TourCategoryController::class, 'index'])->name('tour-categories.all')->middleware('bouncer:TourCategory,read');
            Route::get('/create', [TourCategoryController::class, 'create'])->name('tour-categories.create')->middleware('bouncer:TourCategory,create');
            Route::post('/create', [TourCategoryController::class, 'store'])->name('tour-categories.store')->middleware('bouncer:TourCategory,create');

            Route::prefix('{tourCategory}')->group(function () {
                Route::get('/', [TourCategoryController::class, 'view'])->name('tour-categories.view')->middleware('bouncer:TourCategory,read');
                Route::get('/update', [TourCategoryController::class, 'edit'])->name('tour-categories.edit')->middleware('bouncer:TourCategory,update');
                Route::post('/update', [TourCategoryController::class, 'update'])->name('tour-categories.update')->middleware('bouncer:TourCategory,update');
                Route::post('/delete', [TourCategoryController::class, 'destroy'])->name('tour-categories.delete')->middleware('bouncer:TourCategory,delete');
            });
        });
    });

    Route::prefix('locations')->group(function () {
        Route::prefix('addresses')->group(function () {
            Route::get('/', [AddressController::class, 'index'])->name('addresses.all')->middleware('bouncer:Address,read');
            Route::get('/create', [AddressController::class, 'create'])->name('addresses.create')->middleware('bouncer:Address,create');
            Route::post('/create', [AddressController::class, 'store'])->name('addresses.store')->middleware('bouncer:Address,create');
            Route::prefix('{address}')->group(function () {
                Route::get('/', [AddressController::class, 'view'])->name('addresses.view')->middleware('bouncer:Address,read');
                Route::get('/update', [AddressController::class, 'edit'])->name('addresses.edit')->middleware('bouncer:Address,update');
                Route::post('/update', [AddressController::class, 'update'])->name('addresses.update')->middleware('bouncer:Address,update');
                Route::post('/delete', [AddressController::class, 'destroy'])->name('addresses.delete')->middleware('bouncer:Address,delete');
            });
        });
        Route::prefix('countries')->group(function () {
            Route::get('/', [CountryController::class, 'index'])->name('countries.all')->middleware('bouncer:Country,read');
            Route::get('/create', [CountryController::class, 'create'])->name('countries.create')->middleware('bouncer:Country,create');
            Route::post('/create', [CountryController::class, 'store'])->name('countries.store')->middleware('bouncer:Country,create');
            Route::prefix('{country}')->group(function () {
                Route::get('/', [CountryController::class, 'view'])->name('countries.view')->middleware('bouncer:Country,read');
                Route::get('/update', [CountryController::class, 'edit'])->name('countries.edit')->middleware('bouncer:Country,update');
                Route::post('/update', [CountryController::class, 'update'])->name('countries.update')->middleware('bouncer:Country,update');
                Route::post('/delete', [CountryController::class, 'destroy'])->name('countries.delete')->middleware('bouncer:Country,delete');
            });
        });
        Route::prefix('location-types')->group(function () {
            Route::get('/', [LocationTypeController::class, 'index'])->name('location-types.all')->middleware('bouncer:LocationType,read');
            Route::get('/create', [LocationTypeController::class, 'create'])->name('location-types.create')->middleware('bouncer:LocationType,create');
            Route::post('/create', [LocationTypeController::class, 'store'])->name('location-types.store')->middleware('bouncer:LocationType,create');
            Route::prefix('{locationType}')->group(function () {
                Route::get('/', [LocationTypeController::class, 'view'])->name('location-types.view')->middleware('bouncer:LocationType,read');
                Route::get('/update', [LocationTypeController::class, 'edit'])->name('location-types.edit')->middleware('bouncer:LocationType,update');
                Route::post('/update', [LocationTypeController::class, 'update'])->name('location-types.update')->middleware('bouncer:LocationType,update');
                Route::post('/delete', [LocationTypeController::class, 'destroy'])->name('location-types.delete')->middleware('bouncer:LocationType,delete');
            });
        });
    });

    Route::get('/dash', function () {
        return view('pages.dash');
    })->name('dash');

    Route::prefix('events')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('events.all')->middleware('bouncer:Event,read');
        Route::get('/create', [EventController::class, 'create'])->name('events.create')->middleware('bouncer:Event,create');
        Route::post('/create', [EventController::class, 'store'])->name('events.store')->middleware('bouncer:Event,create');
        Route::prefix('{event}')->group(function () {
            Route::get('/', [EventController::class, 'view'])->name('events.view')->middleware('bouncer:Event,read');
            Route::get('/update', [EventController::class, 'edit'])->name('events.edit')->middleware('bouncer:Event,update');
            Route::post('/update', [EventController::class, 'update'])->name('events.update')->middleware('bouncer:Event,update');
            Route::post('/delete', [EventController::class, 'destroy'])->name('events.delete')->middleware('bouncer:Event,delete');
        });
    });

    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'edit'])->name('settings.edit')->middleware('bouncer:Setting,update');
        Route::post('/', [SettingsController::class, 'update'])->name('settings.update')->middleware('bouncer:Setting,update');
    });

    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('customers.all')->middleware('bouncer:Customer,read');
        Route::get('/create', [CustomerController::class, 'create'])->name('customers.create')->middleware('bouncer:Customer,create');
        Route::post('/create', [CustomerController::class, 'store'])->name('customers.store')->middleware('bouncer:Customer,create');
        Route::prefix('{customer}')->group(function () {
            Route::get('/', [CustomerController::class, 'view'])->name('customers.view')->middleware('bouncer:Customer,read');
            Route::get('/update', [CustomerController::class, 'edit'])->name('customers.edit')->middleware('bouncer:Customer,update');
            Route::post('/update', [CustomerController::class, 'update'])->name('customers.update')->middleware('bouncer:Customer,update');
            Route::post('/delete', [CustomerController::class, 'destroy'])->name('customers.delete')->middleware('bouncer:Customer,delete');
        });
        Route::prefix('t-shirt-sizes')->group(function () {
            Route::get('/', [TShirtSizeController::class, 'index'])->name('t-shirt-sizes.all')->middleware('bouncer:TShirtSize,read');
            Route::get('/create', [TShirtSizeController::class, 'create'])->name('t-shirt-sizes.create')->middleware('bouncer:TShirtSize,create');
            Route::post('/create', [TShirtSizeController::class, 'store'])->name('t-shirt-sizes.store')->middleware('bouncer:TShirtSize,create');
            Route::prefix('{tShirtSize}')->group(function () {
                Route::get('/', [TShirtSizeController::class, 'view'])->name('t-shirt-sizes.view')->middleware('bouncer:TShirtSize,read');
                Route::get('/update', [TShirtSizeController::class, 'edit'])->name('t-shirt-sizes.edit')->middleware('bouncer:TShirtSize,update');
                Route::post('/update', [TShirtSizeController::class, 'update'])->name('t-shirt-sizes.update')->middleware('bouncer:TShirtSize,update');
                Route::post('/delete', [TShirtSizeController::class, 'destroy'])->name('t-shirt-sizes.delete')->middleware('bouncer:TShirtSize,delete');
            });
        });
        Route::prefix('hat-sizes')->group(function () {
            Route::get('/', [HatSizeController::class, 'index'])->name('hat-sizes.all')->middleware('bouncer:HatSize,read');
            Route::get('/create', [HatSizeController::class, 'create'])->name('hat-sizes.create')->middleware('bouncer:HatSize,create');
            Route::post('/create', [HatSizeController::class, 'store'])->name('hat-sizes.store')->middleware('bouncer:HatSize,create');
            Route::prefix('{hatSize}')->group(function () {
                Route::get('/', [HatSizeController::class, 'view'])->name('hat-sizes.view')->middleware('bouncer:HatSize,read');
                Route::get('/update', [HatSizeController::class, 'edit'])->name('hat-sizes.edit')->middleware('bouncer:HatSize,update');
                Route::post('/update', [HatSizeController::class, 'update'])->name('hat-sizes.update')->middleware('bouncer:HatSize,update');
                Route::post('/delete', [HatSizeController::class, 'destroy'])->name('hat-sizes.delete')->middleware('bouncer:HatSize,delete');
            });
        });
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('users.all')->middleware('bouncer:User,read');
        Route::get('/create', [UserController::class, 'create'])->name('users.create')->middleware('bouncer:User,create');
        Route::post('/create', [UserController::class, 'store'])->name('users.store')->middleware('bouncer:User,create');
        Route::prefix('{user}')->group(function () {
            Route::get('/', [UserController::class, 'view'])->name('users.view')->middleware('bouncer:User,read');
            Route::get('/update', [UserController::class, 'edit'])->name('users.edit')->middleware('bouncer:User,update');
            Route::post('/update', [UserController::class, 'update'])->name('users.update')->middleware('bouncer:User,update');
            Route::post('/delete', [UserController::class, 'destroy'])->name('users.delete')->middleware('bouncer:User,delete');
        });
    });

    Route::prefix('roles')->group(function () {
        Route::get('/', [PermissionsController::class, 'index'])->name('roles.all')->middleware('bouncer:User,read');
        Route::get('/create', [PermissionsController::class, 'create'])->name('roles.create')->middleware('bouncer:User,create');
        Route::post('/create', [PermissionsController::class, 'store'])->name('roles.store')->middleware('bouncer:User,create');
        Route::prefix('{role}')->group(function () {
            Route::get('/update', [PermissionsController::class, 'edit'])->name('roles.edit')->middleware('bouncer:User,update');
            Route::post('/update', [PermissionsController::class, 'update'])->name('roles.update')->middleware('bouncer:User,update');
            Route::post('/delete', [PermissionsController::class, 'destroy'])->name('roles.delete')->middleware('bouncer:User,delete');
        });
    });
    Route::prefix('email/')->name('email.')->group(function () {
        Route::prefix('booking')->name('booking.')->group(function () {
            Route::get('/edit', [MailController::class, 'editBooking'])->name('edit');
            Route::post('/edit', [MailController::class, 'storeBooking'])->name('update');
            Route::get('/demo', [MailController::class, 'demoBooking'])->name('demo');
            Route::get('/demo/{order}', [MailController::class, 'demoOrderBooking'])->name('order_demo');
        });
        Route::prefix('due-payment')->name('payment-due.')->group(function () {
            Route::get('/edit', [MailController::class, 'editPaymentDue'])->name('edit');
            Route::post('/edit', [MailController::class, 'storePaymentDue'])->name('update');
            Route::get('/demo', [MailController::class, 'demoPaymentDue'])->name('demo');
            Route::get('/demo/{order}', [MailController::class, 'demoOrderPaymentDue'])->name('order_demo');
        });
        Route::prefix('payment-made')->name('payment-made.')->group(function () {
            Route::get('/edit', [MailController::class, 'editPaymentMade'])->name('edit');
            Route::post('/edit', [MailController::class, 'storePaymentMade'])->name('update');
            Route::get('/demo', [MailController::class, 'demoPaymentMade'])->name('demo');
            Route::get('/demo/{order}', [MailController::class, 'demoOrderPaymentMade'])->name('order_demo');
        });
        Route::prefix('refund-given')->name('refund-given.')->group(function () {
            Route::get('/edit', [MailController::class, 'editRefundGiven'])->name('edit');
            Route::post('/edit', [MailController::class, 'storeRefundGiven'])->name('update');
            Route::get('/demo', [MailController::class, 'demoRefundGiven'])->name('demo');
            Route::get('/demo/{order}', [MailController::class, 'demoOrderRefundGiven'])->name('order_demo');
        });
    });
});

Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('/login', [CustomerPortalController::class, 'showCustomerLogin'])->name('login');
    Route::get('/register', [CustomerPortalController::class, 'showCustomerRegister'])->name('register');
    Route::post('/login', [CustomerPortalController::class, 'login'])->name('confirm-login');
    Route::post('/register', [CustomerPortalController::class, 'register'])->name('confirm-register');
    Route::prefix('{customer}')->group(function () {
        Route::get('/atol', [CustomerPortalController::class, 'showAtol'])->name('atol');
        Route::get('/portal', [CustomerPortalController::class, 'showMainPortal'])->name('portal');
        Route::get('/details', [CustomerPortalController::class, 'showDetailsPage'])->name('details');
        Route::get('/details/edit', [CustomerPortalController::class, 'showEditDetailsPage'])->name('edit');
        Route::get('/finances', [CustomerPortalController::class, 'showFinancesPage'])->name('finances');
    });
});

Auth::routes(['verify' => true,'register' => false]);
Route::get('test/{value}', function(\App\Models\OrderCustomer $value) { dd(OrderRepository::getOrderAddons($value->order));});
