<?php

use App\Http\Controllers\Admin\AdditionalCostController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\AuthenticationController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\Reporting\BespokeReportController;
use App\Http\Controllers\Admin\System\ImportController;
use App\Http\Controllers\Admin\System\MailController;
use App\Http\Controllers\Admin\System\NotificationController;
use App\Http\Controllers\Admin\System\PermissionsController;
use App\Http\Controllers\Admin\System\SettingsController;
use App\Http\Controllers\Admin\TravelClassController;
use App\Http\Controllers\Admin\User\UserProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\Voucher\VoucherCodeController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthenticationController::class, 'showLogin'])->name('show-login');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
Route::post('/logout', [AuthenticationController::class, 'logout'])->name('logout');

Route::prefix('password')->name('password.')->group(function () {
    Route::get('/confirm', [AuthenticationController::class, 'viewConfirmDialog'])->name('confirm');
    Route::post('/confirm', [AuthenticationController::class, 'confirmPassword'])->name('confirm-password');
    Route::get('/forgot', [AuthenticationController::class, 'forgot'])->name('forgot');
    Route::post('/forgot', [AuthenticationController::class, 'sendForgotEmail'])->name('send-reset');
    Route::get('/reset', [AuthenticationController::class, 'getNewPassword'])->name('get-new');
    Route::post('/reset', [AuthenticationController::class, 'resetPassword'])->name('reset');
});

Route::emailVerification();

Route::middleware('auth:web')->group(function () {

    Route::prefix('orders')->group(__DIR__ . '/admin/order.php');

    Route::prefix('customers')->group(__DIR__ . '/admin/customer.php');

    Route::prefix('quotes')->name('quotes.')->group(__DIR__ . '/admin/quote.php');

    Route::prefix('tours')->group(__DIR__ . '/admin/tour.php');

    Route::prefix('events')->name('events.')->group(__DIR__ . '/admin/event.php');

    Route::prefix('accommodation')->group(__DIR__ . '/admin/component/accommodation.php');

    Route::prefix('activities')->group(__DIR__ . '/admin/component/activity.php');

    Route::prefix('flights')->group(__DIR__ . '/admin/component/flight.php');

    Route::prefix('transports')->group(__DIR__ . '/admin/component/transport.php');

    Route::prefix('merchandise')->name('merchandise.')->group(__DIR__ . '/admin/component/merchandise.php');

    Route::prefix('supplier')->name('supplier.')->group(__DIR__ . '/admin/supplier.php');

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

    Route::prefix('bookings')->name('admin.booking.')->group(function () {
        Route::prefix('/{booking}')->group(function () {
            Route::get('/', [BookingController::class, 'view'])->name('view');
            Route::post('/convert', [BookingController::class, 'convert'])->name('convert');
        });
    });

    Route::prefix('locations')->group(__DIR__ . '/admin/location.php');

    // Route::get('/', function () {
    //     return view('pages.dash');
    // })->name('dash');
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dash');

    Route::get('/attributes', function () {
        return view('pages.admin.small-models');
    })->name('attributes.edit');

    Route::prefix('settings')->group(function () {
        Route::middleware('bouncer:System\Setting,update')->name('settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'edit'])->name('edit');
            Route::post('/', [SettingsController::class, 'update'])->name('update');
        });
        Route::get('/mail', [SettingsController::class, 'mail'])->name('settings.mail');
        Route::get('/import', [SettingsController::class, 'import'])->name('settings.import');
        Route::get('/template', [SettingsController::class, 'template'])->name('settings.template');
        Route::get('/template/edit/{template?}', [SettingsController::class, 'editTemplate'])->name('settings.template.form');
        Route::get('export/conversion-rates/{extension}', [SettingsController::class, 'exportConversionRates'])->name('export.conversion-rates');
        Route::prefix('import')->name('import.')->group(function () {
            Route::post('/customer', [ImportController::class, 'customer'])->name('customer');
            Route::post('/organization', [ImportController::class, 'organization'])->name('organization');
            Route::post('/accommodation', [ImportController::class, 'accommodation'])->name('accommodation');
            Route::post('/accommodation/inventory', [ImportController::class, 'accommodationInventory'])->name('accommodation.inventory');
            Route::post('/activity', [ImportController::class, 'activity'])->name('activity');
            Route::post('/activity/inventory', [ImportController::class, 'activityInventory'])->name('activity.inventory');
            Route::post('/operator', [ImportController::class, 'operator'])->name('operator');
            Route::post('/conversion-rate', [ImportController::class, 'conversionRate'])->name('conversion-rate');
        });
        Route::prefix('email/')->name('email.')->group(function () {
            Route::prefix('{mail}')->group(function () {
                Route::get('/edit', [MailController::class, 'edit'])->name('edit');
                Route::post('/edit', [MailController::class, 'update'])->name('update');
                Route::get('/demo', [MailController::class, 'demo'])->name('demo');
            });
        });
    });
    Route::prefix('vouchers')->name('vouchers.')->group(function () {
       Route::get('/', [VoucherCodeController::class, 'index'])->name('index');
       Route::get('/{voucher}', [VoucherCodeController::class, 'view'])->name('view');
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
    
            Route::prefix('agents')->group(function () {
                Route::get('/', [AgentController::class, 'index'])->name('agents.all');
                Route::get('/create', [AgentController::class, 'create'])->name('agents.create');
                Route::post('/create', [AgentController::class, 'store'])->name('agents.store');
                Route::prefix('{agent}')->group(function () {
                    Route::get('/', [AgentController::class, 'view'])->name('agents.view');
                    Route::get('/update', [AgentController::class, 'edit'])->name('agents.edit');
                    Route::post('/update', [AgentController::class, 'update'])->name('agents.update');
                    Route::post('/delete', [AgentController::class, 'destroy'])->name('agents.delete');
                    Route::post('/delete-agent', [AgentController::class, 'deleteAgent'])->name('agents.deleteAgent');
                });
            });
        });
    });

    Route::prefix('users')->group(function () {
        Route::get('/create', [UserController::class, 'create'])->name('users.create')->middleware('bouncer:User,create');
        Route::post('/create', [UserController::class, 'store'])->name('users.store')->middleware('bouncer:User,create');
    });

    Route::prefix('users')->name('users.')->group(function () {
       Route::get('/', [UserController::class, 'index'])->name('all')->middleware('bouncer:User,read');
       Route::middleware('password.confirm')->group(function () {
           Route::get('/{user?}', [UserProfileController::class, 'profile'])->name('profile');
           Route::post('/avatar/{user?}', [UserProfileController::class, 'avatar'])->name('avatar');
           Route::post('/update/{user?}', [UserProfileController::class, 'update'])->name('update');
           Route::post('/reset/{user?}', [UserProfileController::class, 'sendReset'])->name('reset');
           Route::post('/password/{user?}', [UserProfileController::class, 'password'])->name('password');
           Route::prefix('2fa')->name('2fa.')->group(function () {
               Route::post('/enable/{user?}', [UserProfileController::class, 'enable2fa'])->name('enable');
               Route::post('/disable/{user?}', [UserProfileController::class, 'disable2fa'])->name('disable');
               Route::post('/disable/force/{user}', [UserProfileController::class, 'forceDisable2fa'])->name('disable.force');
           });
           Route::prefix('{user}')->group(function () {
               Route::post('/delete', [UserController::class, 'destroy'])->name('delete')->middleware('bouncer:User,delete');
               Route::post('/restore', [UserController::class, 'restore'])->name('restore')->middleware('bouncer:User,delete');
           });
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
        Route::prefix('bespoke')->group(__DIR__ . '/admin/report/bespoke.php');
        Route::prefix('advanced')->group(__DIR__ . '/admin/report/advanced.php');
        Route::prefix('/')->group(__DIR__ . '/admin/report/system.php');
    });

    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'center'])->name('notifications.all');
    });
});
