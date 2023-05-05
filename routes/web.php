<?php

use App\Http\Controllers\Admin\AdditionalCostController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\System\ImportController;
use App\Http\Controllers\BespokeReportController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Customer\CustomerBookingController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\Models\AddressController;
use App\Http\Controllers\Models\CountryController;
use App\Http\Controllers\Models\LocationTypeController;
use App\Http\Controllers\Models\TravelClassController;
use App\Http\Controllers\Models\UserController;
use App\Http\Controllers\PaymentScheduleController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TourController;
use App\Http\Gateways\FellohGateway;
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
    return redirect()->route('dash');
})->name('homepage');

Route::get('/homepage', function () {
    return view('pages.homepage');
});

Route::get('/pdfmake', function () {
    return view('pdf.atol');
});

Route::prefix("/vue-booking")->group(function () {

    // laravel route (not booking form)
    Route::post('/deposit/payment', [BookingController::class, 'payDeposit']);

    // debugging routes
    Route::get('/check/events', [TourController::class, 'getEvents']);
    Route::get('/check/tour/{event_id}', [TourController::class, 'getTours']);

    Route::get('/check/apitests', function () {
        return view('frontend-tests/apitests');
    });
    Route::get('/check/vuetest', function () {
        return view('frontend-tests/vuetest');
    });
    Route::get('/check/atoltest', function () {
        return view('frontend-tests/atoltest');
    });

    Route::get('/store', function () {
        return view('pages.booking.store');
    });

    // booking form recovery and accessors not used
    // Route::get('/login/{token}', [BookingFormLoginController::class, 'loginWithToken']); // Demo for now
    // Route::get('/edit/{id}', [BookingController::class, 'bookingForm']);
    // Route::get('/tour/{url}', [BookingController::class, 'bookingForm']);
    // Route::get('/event/{url}', [BookingController::class, 'eventBookingForm']);
    // Route::get('/', [BookingController::class, 'bookingForm']);

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

Route::prefix('admin')->group(function () {
    Auth::routes(['verify' => true, 'register' => false]);
});

Route::middleware('auth:web')->prefix('admin')->group(function () {

    Route::prefix('orders')->group(__DIR__ . '/web/admin/order.php');

    Route::prefix('customers')->group(__DIR__ . '/web/admin/customer.php');

    Route::prefix('quotes')->name('quotes.')->group(__DIR__ . '/web/admin/quote.php');

    Route::prefix('tours')->group(__DIR__ . '/web/admin/tour.php');

    Route::prefix('events')->group(__DIR__ . '/web/admin/event.php');

    Route::prefix('accommodation')->group(__DIR__ . '/web/admin/component/accommodation.php');

    Route::prefix('activities')->group(__DIR__ . '/web/admin/component/activity.php');

    Route::prefix('flights')->group(__DIR__ . '/web/admin/component/flight.php');

    Route::prefix('transports')->group(__DIR__ . '/web/admin/component/transport.php');

    Route::prefix('merchandise')->name('merchandise.')->group(__DIR__ . '/web/admin/component/merchandise.php');

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

    Route::prefix('cost')->group(function () {
        Route::post('/{model}/{id}/create/', [AdditionalCostController::class, 'store'])->name('additional-cost.store');
        Route::prefix('{cost}')->group(function () {
            Route::post('/update', [AdditionalCostController::class, 'update'])->name('additional-cost.update');
            Route::post('/delete', [AdditionalCostController::class, 'destroy'])->name('additional-cost.delete');
        });
    });

    Route::prefix('locations')->group(function () {
        Route::prefix('addresses')->group(function () {
            Route::get('/', [AddressController::class, 'index'])->name('addresses.all')->middleware('bouncer:Location\Address,read');
            Route::get('/create/{addressParent}', [AddressController::class, 'create'])->name('addresses.create')->middleware('bouncer:Location\Address,create');
            Route::post('/create/{addressParent}', [AddressController::class, 'store'])->name('addresses.store')->middleware('bouncer:Location\Address,create');
            Route::prefix('{address}')->group(function () {
                Route::get('/', [AddressController::class, 'view'])->name('addresses.view')->middleware('bouncer:Location\Address,read');
                Route::get('/update', [AddressController::class, 'edit'])->name('addresses.edit')->middleware('bouncer:Location\Address,update');
                Route::post('/update', [AddressController::class, 'update'])->name('addresses.update')->middleware('bouncer:Location\Address,update');
                Route::post('/delete', [AddressController::class, 'destroy'])->name('addresses.delete')->middleware('bouncer:Location\Address,delete');
            });
        });
        Route::prefix('countries')->group(function () {
            Route::get('/', [CountryController::class, 'index'])->name('countries.all')->middleware('bouncer:Location\Country,read');
            Route::get('/create', [CountryController::class, 'create'])->name('countries.create')->middleware('bouncer:Location\Country,create');
            Route::post('/create', [CountryController::class, 'store'])->name('countries.store')->middleware('bouncer:Location\Country,create');
            Route::prefix('{country}')->group(function () {
                Route::get('/', [CountryController::class, 'view'])->name('countries.view')->middleware('bouncer:Location\Country,read');
                Route::get('/update', [CountryController::class, 'edit'])->name('countries.edit')->middleware('bouncer:Location\Country,update');
                Route::post('/update', [CountryController::class, 'update'])->name('countries.update')->middleware('bouncer:Location\Country,update');
                Route::post('/delete', [CountryController::class, 'destroy'])->name('countries.delete')->middleware('bouncer:Location\Country,delete');
            });
        });
        Route::prefix('location-types')->group(function () {
            Route::get('/', [LocationTypeController::class, 'index'])->name('location-types.all')->middleware('bouncer:Location\LocationType,read');
            Route::get('/create', [LocationTypeController::class, 'create'])->name('location-types.create')->middleware('bouncer:Location\LocationType,create');
            Route::post('/create', [LocationTypeController::class, 'store'])->name('location-types.store')->middleware('bouncer:Location\LocationType,create');
            Route::prefix('{locationType}')->group(function () {
                Route::get('/', [LocationTypeController::class, 'view'])->name('location-types.view')->middleware('bouncer:Location\LocationType,read');
                Route::get('/update', [LocationTypeController::class, 'edit'])->name('location-types.edit')->middleware('bouncer:Location\LocationType,update');
                Route::post('/update', [LocationTypeController::class, 'update'])->name('location-types.update')->middleware('bouncer:Location\LocationType,update');
                Route::post('/delete', [LocationTypeController::class, 'destroy'])->name('location-types.delete')->middleware('bouncer:Location\LocationType,delete');
            });
        });
    });

    Route::get('/', function () {
        return view('pages.dash');
    })->name('dash');

    Route::get('/attributes', function () {
        return view('pages.admin.small-models');
    })->name('attributes.edit');

    Route::prefix('settings')->group(function () {
        Route::middleware('bouncer:System\Setting,update')->name('settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'edit'])->name('edit');
            Route::post('/', [SettingsController::class, 'update'])->name('update');
        });
        Route::prefix('import')->name('import.')->group(function () {
            Route::post('/customer', [ImportController::class, 'customer'])->name('customer');
            Route::post('/accommodation', [ImportController::class, 'accommodation'])->name('accommodation');
            Route::post('/accommodation/inventory', [ImportController::class, 'accommodationInventory'])->name('accommodation.inventory');
            Route::post('/activity', [ImportController::class, 'activity'])->name('activity');
            Route::post('/activity/inventory', [ImportController::class, 'activityInventory'])->name('activity.inventory');
        });
        Route::prefix('email/')->name('email.')->group(function () {
            Route::prefix('{mail}')->group(function () {
                Route::get('/edit', [MailController::class, 'edit'])->name('edit');
                Route::post('/edit', [MailController::class, 'update'])->name('update');
                Route::get('/demo', [MailController::class, 'demo'])->name('demo');
            });
        });
    });

    Route::prefix('organizations')->group(function () {
        Route::get('/', [OrganizationController::class, 'index'])->name('organizations.all')->middleware('bouncer:Customer\Customer,read');
        Route::get('/create', [OrganizationController::class, 'create'])->name('organizations.create')->middleware('bouncer:Customer\Customer,create');
        Route::post('/create', [OrganizationController::class, 'store'])->name('organizations.store')->middleware('bouncer:Customer\Customer,create');
        Route::prefix('{organization}')->group(function () {
            Route::get('/', [OrganizationController::class, 'view'])->name('organizations.view')->middleware('bouncer:Customer\Customer,read');
            Route::get('/update', [OrganizationController::class, 'edit'])->name('organizations.edit')->middleware('bouncer:Customer\Customer,update');
            Route::post('/update', [OrganizationController::class, 'update'])->name('organizations.update')->middleware('bouncer:Customer\Customer,update');
            Route::post('/delete', [OrganizationController::class, 'destroy'])->name('organizations.delete')->middleware('bouncer:Customer\Customer,delete');
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
            Route::post('/restore', [UserController::class, 'restore'])->name('users.restore')->middleware('bouncer:User,delete');
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

    Route::prefix('reports')->group(function () {
        Route::get('/', [BespokeReportController::class, 'index'])->name('reports.all');
        Route::prefix('bespoke')->group(__DIR__ . '/web/admin/report/bespoke.php');
        Route::prefix('/')->group(__DIR__ . '/web/admin/report/system.php');
    });
});

Route::prefix('customer')->name('customer.')->group(__DIR__ . '/web/customer.php');

Route::prefix('payment')->name('payment.')->group(function () {
    Route::prefix('gateway')->name('gateway.')->group(function () {
        Route::prefix('stripe')->name('stripe.')->group(function () {
            Route::get('success', [StripeController::class, 'success'])->name('success');
            Route::get('cancelled', [StripeController::class, 'cancelled'])->name('cancelled');
        });
        Route::prefix('felloh')->name('felloh.')->group(function () {
            Route::get('failed', [FellohGateway::class, 'failed'])->name('failed');
        });
    });
});

Route::prefix('/booking/{bookingUrl}')->group(function () {
    Route::get('/{token?}', [CustomerBookingController::class, 'index'])->name('customer-booking.index');
    Route::post('/{token?}', [CustomerBookingController::class, 'storeCustomers'])->name('customer-booking.store-customers');
    Route::get('/{token}/summary', [CustomerBookingController::class, 'components'])->name('customer-booking.summary');
    Route::get('/{token}/rooming', [CustomerBookingController::class, 'rooming'])->name('customer-booking.rooming');
    Route::post('/{token}/pay', [CustomerBookingController::class, 'payDeposit'])->name('customer-booking.deposit');
    Route::get('/{token}/addon/purchase/{id}/{type}', [CustomerBookingController::class, 'purchaseAddon'])->name('customer-booking.purchase-addon');
    Route::get('/{token}/addon/remove/{id}/{type}', [CustomerBookingController::class, 'removeAddon'])->name('customer-booking.remove-addon');
});
