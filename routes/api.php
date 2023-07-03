<?php
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Admin\Reporting\BespokeReportController;
use App\Http\Controllers\Api\AccommodationController;
use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\Admin\MerchandiseController;
use App\Http\Controllers\Api\Admin\QuoteController;
use App\Http\Controllers\Api\Admin\RevenueController;
use App\Http\Controllers\Api\CustomerBookingController;
use App\Http\Controllers\Api\CustomerComponentController;
use App\Http\Controllers\Api\DataTablesController;
use App\Http\Controllers\Api\FlightController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\SelectController;
use App\Http\Controllers\Api\TourComponentController;
use App\Http\Controllers\Api\TransportController;
use App\Http\Gateways\FellohGateway;
use Illuminate\Support\Facades\Route;


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
});

Route::stripeWebhooks('/stripe/webhooks');
Route::post('/felloh/webhook', [FellohGateway::class, 'webhook'])->name('api.felloh.webhook');

Route::post('/dual/select/countries', [SelectController::class, 'getCountries'])->name('api.countries.select');

Route::prefix('/php/booking')->name('api.booking.')->group(function () {
    Route::post('/upgrade/activity/{token}', [CustomerBookingController::class, 'upgradeActivity'])->name('upgrade-activity');
    Route::post('/customer/remove/{token}', [CustomerBookingController::class, 'removeCustomer'])->name('remove-customer');
    Route::post('/rooming/get/{bookingUrl}/{token}', [CustomerBookingController::class, 'getRoomingInformation'])->name('rooming.get');
    Route::post('/rooming/save/{bookingUrl}/{token}', [CustomerBookingController::class, 'saveRoomingInformation'])->name('rooming.save');
});

Route::post('/admin/orders/{order}/rooming/get', [OrderController::class, 'getRoomingInformation'])->name('api.orders.rooming.get');

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

    Route::prefix('orders')->name('order.')->group(function () {
        Route::prefix('addons')->name('addon.')->group(function () {
            Route::prefix('add')->name('add.')->group(function () {
                Route::post('/accommodation/add', [TourComponentController::class, 'addAccommodationAddon'])->name('accommodation');
                Route::post('/activity/add', [TourComponentController::class, 'addActivityAddon'])->name('activity');
                Route::post('/flight/add', [TourComponentController::class, 'addFlightAddon'])->name('flight');
                Route::post('/transport/add', [TourComponentController::class, 'addTransportAddon'])->name('transport');
                Route::post('/merchandise/add', [TourComponentController::class, 'addMerchandiseAddon'])->name('merchandise');
            });
        });
        Route::post('upgrade', [CustomerComponentController::class, 'apply'])->name('customer.upgrade');
        Route::post('upgrade/buy', [CustomerComponentController::class, 'purchase'])->name('customer.upgrade.purchase');
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
    Route::prefix('/costing')->name('costing.')->group(function () {
        Route::get('/revenue', [RevenueController::class, 'revenue'])->name('revenue');
        Route::get('/revenue/set', [RevenueController::class, 'revenueSet'])->name('revenue.set');
    });
    Route::post('/merchandise/fulfil', [MerchandiseController::class, 'fulfil'])->name('merchandise.fulfil');
    Route::post('accommodation/rooming/{order}/save', [AccommodationController::class, 'saveRoomingData'])->name('roomings.save');
    Route::post('/orders', [OrderController::class, 'getOverview'])->name('orders.all');
    Route::prefix('select')->group(__DIR__ . '/api/select.php');

    Route::prefix('datatables')->group(function () {
        Route::post('accommodation-inventory/{tour}', [DataTablesController::class, 'getAccommodationInventoryComponents'])->name('accommodation-inventory.datatables');
        Route::post('activity-inventory/{tour}', [DataTablesController::class, 'getActivityInventoryComponents'])->name('activity-inventory.datatables');
        Route::post('flight-inventory/{tour}', [DataTablesController::class, 'getFlightInventoryComponents'])->name('flight-inventory.datatables');
        Route::post('transport-inventory/{tour}', [DataTablesController::class, 'getTransportInventoryComponents'])->name('transport-inventory.datatables');
    });

    Route::prefix('component')->group(function () {
        Route::post('/quote/{quote}/{type}/add', [QuoteController::class, 'addComponents'])->name('quote.components.add');
        Route::prefix('tour/{tour}')->group(function () {
            Route::prefix('accommodation/inventory')->group(function () {
                Route::post('/add', [AccommodationController::class, 'addAccommodationInventoryToTour'])->name('tour.accommodation.inventory.add');
            });
            Route::prefix('activity/inventory')->group(function () {
                Route::post('/add', [ActivityController::class, 'addActivityInventoryToTour'])->name('tour.activity.inventory.add');
            });
            Route::prefix('flight/inventory')->group(function () {
                Route::post('/add', [FlightController::class, 'addFlightInventoryToTour'])->name('tour.flight.inventory.add');
            });
            Route::prefix('transport/inventory')->group(function () {
                Route::post('/add', [TransportController::class, 'addTransportInventoryToTour'])->name('tour.transport.inventory.add');
            });
            Route::prefix('merchandise/inventory')->group(function () {
                Route::post('/add', [MerchandiseController::class, 'addMerchandiseToTour'])->name('tour.merchandise.inventory.add');
            });
        });
    });

    Route::prefix('orders')->name('order.')->group(function () {
        Route::post('/unknown/{order?}', [OrderController::class, 'generateUnknown'])->name('unknown-traveller');
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
        Route::post('resend/booking-confirmation', [OrderController::class, 'resendOrderConfirmation'])->name('resend.booking-confirmation');
    });

    Route::prefix('quotes')->name('quote.')->group(function () {
        Route::prefix('{quote}')->group(function () {
            Route::get('/cost', [QuoteController::class, 'getCost'])->name('cost');
            Route::post('/unknown', [QuoteController::class, 'getUnknownTraveller'])->name('unknown-traveller');
        });
    });

    Route::prefix('reports')->name('reports.')->group(function () {
        Route::post('bespoke/save', [BespokeReportController::class, 'store'])->name('bespoke.save');
        Route::post('bespoke/export', [BespokeReportController::class, 'apiExport'])->name('bespoke.export');
    });
});
