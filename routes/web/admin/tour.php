<?php

use App\Http\Controllers\Admin\Accommodation\AccommodationInventoryTourController;
use App\Http\Controllers\Admin\Activity\ActivityInventoryTourController;
use App\Http\Controllers\Admin\Flight\FlightInventoryTourController;
use App\Http\Controllers\Admin\Merchandise\MerchandiseInventoryTourController;
use App\Http\Controllers\Admin\Tour\Component\UpgradeController;
use App\Http\Controllers\Admin\Tour\PaymentInstallmentController;
use App\Http\Controllers\Admin\Tour\TourCategoryController;
use App\Http\Controllers\Admin\Tour\TourController;
use App\Http\Controllers\Admin\Tour\TourManifestController;
use App\Http\Controllers\Admin\Transport\TransportInventoryTourController;
use App\Models\Tour\Tour;

Route::get('/', [TourController::class, 'index'])->name('tours.all')->middleware('bouncer:Tour\Tour,read');
Route::get('/create', [TourController::class, 'create'])->name('tours.create')->middleware('bouncer:Tour\Tour,create');
Route::post('/create', [TourController::class, 'store'])->name('tours.store')->middleware('bouncer:Tour\Tour,create');
Route::prefix('{tour}')->group(function () {
    Route::get('/', [TourController::class, 'view'])->name('tours.view')->middleware('bouncer:Tour\Tour,read');
    Route::get('/costing', [TourController::class, 'costing'])->name('tours.costing')->middleware('bouncer:Tour\Tour,costing');
    Route::get('/update', [TourController::class, 'edit'])->name('tours.edit')->middleware('bouncer:Tour\Tour,update');
    Route::post('/update', [TourController::class, 'update'])->name('tours.update')->middleware('bouncer:Tour\Tour,update');
    Route::get('/duplicate', [TourController::class, 'duplicate'])->name('tours.duplicate')->middleware('bouncer:Tour\Tour,create');
    Route::post('/delete', [TourController::class, 'destroy'])->name('tours.delete')->middleware('bouncer:Tour\Tour,delete');
    Route::post('/restore', [TourController::class, 'restore'])->name('tours.restore')->middleware('bouncer:Tour\Tour,delete');
    Route::get('/atol', [TourController::class, 'exportAtol'])->name('tours.atol')->middleware('bouncer:Tour\Tour,read');
    Route::get('/add', function (Tour $tour) {
        return view('pages.tour.components.add', ['tour' => $tour,]);
    })->name('tours.add')->middleware('bouncer:Tour\Tour,update');
    Route::get('/fulfil', [TourController::class, 'fulfil'])->name('tours.fulfil')->middleware('bouncer:Merchandise\Merchandise,update');
    Route::get('/rooming', [TourManifestController::class, 'rooming'])->name('tours.rooming')->middleware('bouncer:Tour\Tour,read');
    Route::get('/rooming/{extension}', [TourManifestController::class, 'exportRooming'])->name('tours.rooming.export')->middleware('bouncer:Tour\Tour,read');
    Route::prefix('/manifest')->name('tours.manifest.')->group(function () {
        Route::get('/activity', [TourManifestController::class, 'activity'])->name('activity.view');
        Route::get('/activity/export/{extension?}', [TourManifestController::class, 'exportActivity'])->name('activity.export');
        Route::get('/flight', [TourManifestController::class, 'flight'])->name('flight.view');
        Route::get('/flight/export/{extension?}', [TourManifestController::class, 'exportFlight'])->name('flight.export');
        Route::get('/transport', [TourManifestController::class, 'transport'])->name('transport.view');
        Route::get('/transport/export/{extension?}', [TourManifestController::class, 'exportTransport'])->name('transport.export');
    });
    Route::prefix('inventory')->group(function () {
        Route::prefix('accommodation')->group(function () {
            Route::get('/create', [AccommodationInventoryTourController::class, 'create'])->name('accommodation-inventory-tours.create')->middleware('bouncer:Accommodation\AccommodationInventoryTour,create');
            Route::post('/create', [AccommodationInventoryTourController::class, 'store'])->name('accommodation-inventory-tours.store')->middleware('bouncer:Accommodation\AccommodationInventoryTour,create');
            Route::prefix('{accommodationInventoryTour}')->group(function () {
                Route::get('/', [AccommodationInventoryTourController::class, 'view'])->name('accommodation-inventory-tours.view')->middleware('bouncer:Accommodation\AccommodationInventoryTour,read');
                Route::get('/update', [AccommodationInventoryTourController::class, 'edit'])->name('accommodation-inventory-tours.edit')->middleware('bouncer:Accommodation\AccommodationInventoryTour,update');
                Route::post('/update', [AccommodationInventoryTourController::class, 'update'])->name('accommodation-inventory-tours.update')->middleware('bouncer:Accommodation\AccommodationInventoryTour,update');
                Route::post('/delete', [AccommodationInventoryTourController::class, 'destroy'])->name('accommodation-inventory-tours.delete')->middleware('bouncer:Accommodation\AccommodationInventoryTour,delete');
                Route::post('/restore', [AccommodationInventoryTourController::class, 'restore'])->name('accommodation-inventory-tours.restore')->middleware('bouncer:Accommodation\AccommodationInventoryTour,delete');
            });
            Route::prefix('upgrade/{inventoryTour}')->group(function () {
                Route::get('/', [UpgradeController::class, 'viewAccommodationUpgrade'])->name('accommodation-upgrade.view')->middleware('bouncer:Accommodation\AccommodationInventoryTour,read');
                Route::get('/create', [UpgradeController::class, 'createAccommodationUpgrade'])->name('accommodation-upgrade.create')->middleware('bouncer:Accommodation\AccommodationInventoryTour,create');
                Route::post('/store', [UpgradeController::class, 'storeAccommodationUpgrade'])->name('accommodation-upgrade.store')->middleware('bouncer:Accommodation\AccommodationInventoryTour,create');
                Route::prefix('{upgrade}')->group(function () {
                    Route::get('/update', [UpgradeController::class, 'editAccommodationUpgrade'])->name('accommodation-upgrade.edit')->middleware('bouncer:Accommodation\AccommodationInventoryTour,update');
                    Route::post('/update', [UpgradeController::class, 'updateAccommodationUpgrade'])->name('accommodation-upgrade.update')->middleware('bouncer:Accommodation\AccommodationInventoryTour,update');
                    Route::post('/delete', [UpgradeController::class, 'deleteAccommodationUpgrade'])->name('accommodation-upgrade.delete')->middleware('bouncer:Accommodation\AccommodationInventoryTour,delete');
                });
            });
        });
        Route::prefix('activity')->group(function () {
            Route::get('/create', [ActivityInventoryTourController::class, 'create'])->name('activity-inventory-tours.create')->middleware('bouncer:Activity\ActivityInventoryTour,create');
            Route::post('/create', [ActivityInventoryTourController::class, 'store'])->name('activity-inventory-tours.store')->middleware('bouncer:Activity\ActivityInventoryTour,create');
            Route::prefix('{activityInventoryTour}')->group(function () {
                Route::get('/', [ActivityInventoryTourController::class, 'view'])->name('activity-inventory-tours.view')->middleware('bouncer:Activity\ActivityInventoryTour,read');
                Route::get('/update', [ActivityInventoryTourController::class, 'edit'])->name('activity-inventory-tours.edit')->middleware('bouncer:Activity\ActivityInventoryTour,update');
                Route::post('/update', [ActivityInventoryTourController::class, 'update'])->name('activity-inventory-tours.update')->middleware('bouncer:Activity\ActivityInventoryTour,update');
                Route::post('/delete', [ActivityInventoryTourController::class, 'destroy'])->name('activity-inventory-tours.delete')->middleware('bouncer:Activity\ActivityInventoryTour,delete');
                Route::post('/restore', [ActivityInventoryTourController::class, 'restore'])->name('activity-inventory-tours.restore')->middleware('bouncer:Activity\ActivityInventoryTour,delete');
            });
            Route::prefix('upgrade/{inventoryTour}')->group(function () {
                Route::get('/', [UpgradeController::class, 'viewActivityUpgrade'])->name('activity-upgrade.view')->middleware('bouncer:Activity\ActivityInventoryTour,read');
                Route::get('/create', [UpgradeController::class, 'createActivityUpgrade'])->name('activity-upgrade.create')->middleware('bouncer:Activity\ActivityInventoryTour,create');
                Route::post('/store', [UpgradeController::class, 'storeActivityUpgrade'])->name('activity-upgrade.store')->middleware('bouncer:Activity\ActivityInventoryTour,create');
                Route::prefix('{upgrade}')->group(function () {
                    Route::get('/update', [UpgradeController::class, 'editActivityUpgrade'])->name('activity-upgrade.edit')->middleware('bouncer:Activity\ActivityInventoryTour,update');
                    Route::post('/update', [UpgradeController::class, 'updateActivityUpgrade'])->name('activity-upgrade.update')->middleware('bouncer:Activity\ActivityInventoryTour,update');
                    Route::post('/delete', [UpgradeController::class, 'deleteActivityUpgrade'])->name('activity-upgrade.delete')->middleware('bouncer:Activity\ActivityInventoryTour,delete');
                });
            });
        });
        Route::prefix('merchandise')->name('merchandise.inventory.tour.')->group(function () {
            Route::prefix('{inventoryTour}')->group(function () {
                Route::get('update', [MerchandiseInventoryTourController::class, 'edit'])->name('edit');
                Route::post('update', [MerchandiseInventoryTourController::class, 'update'])->name('update');
                Route::post('delete', [MerchandiseInventoryTourController::class, 'delete'])->name('delete');
                Route::post('restore', [MerchandiseInventoryTourController::class, 'restore'])->name('restore');
            });
        });
        Route::prefix('flight')->group(function () {
            Route::get('/create', [FlightInventoryTourController::class, 'create'])->name('flight-inventory-tours.create')->middleware('bouncer:Flight\FlightInventoryTour,create');
            Route::post('/create', [FlightInventoryTourController::class, 'store'])->name('flight-inventory-tours.store')->middleware('bouncer:Flight\FlightInventoryTour,create');
            Route::prefix('{flightInventoryTour}')->group(function () {
                Route::get('/', [FlightInventoryTourController::class, 'view'])->name('flight-inventory-tours.view')->middleware('bouncer:Flight\FlightInventoryTour,read');
                Route::get('/update', [FlightInventoryTourController::class, 'edit'])->name('flight-inventory-tours.edit')->middleware('bouncer:Flight\FlightInventoryTour,update');
                Route::post('/update', [FlightInventoryTourController::class, 'update'])->name('flight-inventory-tours.update')->middleware('bouncer:Flight\FlightInventoryTour,update');
                Route::post('/delete', [FlightInventoryTourController::class, 'destroy'])->name('flight-inventory-tours.delete')->middleware('bouncer:Flight\FlightInventoryTour,delete');
                Route::post('/restore', [FlightInventoryTourController::class, 'restore'])->name('flight-inventory-tours.restore')->middleware('bouncer:Flight\FlightInventoryTour,delete');
            });
            Route::prefix('upgrade/{inventoryTour}')->group(function () {
                Route::get('/', [UpgradeController::class, 'viewFlightUpgrade'])->name('flight-upgrade.view')->middleware('bouncer:Flight\FlightInventoryTour,read');
                Route::get('/create', [UpgradeController::class, 'createFlightUpgrade'])->name('flight-upgrade.create')->middleware('bouncer:Flight\FlightInventoryTour,create');
                Route::post('/store', [UpgradeController::class, 'storeFlightUpgrade'])->name('flight-upgrade.store')->middleware('bouncer:Flight\FlightInventoryTour,create');
                Route::prefix('{upgrade}')->group(function () {
                    Route::get('/update', [UpgradeController::class, 'editFlightUpgrade'])->name('flight-upgrade.edit')->middleware('bouncer:Flight\FlightInventoryTour,update');
                    Route::post('/update', [UpgradeController::class, 'updateFlightUpgrade'])->name('flight-upgrade.update')->middleware('bouncer:Flight\FlightInventoryTour,update');
                    Route::post('/delete', [UpgradeController::class, 'deleteFlightUpgrade'])->name('flight-upgrade.delete')->middleware('bouncer:Flight\FlightInventoryTour,delete');
                });
            });
        });
        Route::prefix('transport')->group(function () {
            Route::get('/create', [TransportInventoryTourController::class, 'create'])->name('transport-inventory-tours.create')->middleware('bouncer:Transport\TransportInventoryTour,create');
            Route::post('/create', [TransportInventoryTourController::class, 'store'])->name('transport-inventory-tours.store')->middleware('bouncer:Transport\TransportInventoryTour,create');
            Route::prefix('{transportInventoryTour}')->group(function () {
                Route::get('/', [TransportInventoryTourController::class, 'view'])->name('transport-inventory-tours.view')->middleware('bouncer:Transport\TransportInventoryTour,read');
                Route::get('/update', [TransportInventoryTourController::class, 'edit'])->name('transport-inventory-tours.edit')->middleware('bouncer:Transport\TransportInventoryTour,update');
                Route::post('/update', [TransportInventoryTourController::class, 'update'])->name('transport-inventory-tours.update')->middleware('bouncer:Transport\TransportInventoryTour,update');
                Route::post('/delete', [TransportInventoryTourController::class, 'destroy'])->name('transport-inventory-tours.delete')->middleware('bouncer:Transport\TransportInventoryTour,delete');
                Route::post('/restore', [TransportInventoryTourController::class, 'restore'])->name('transport-inventory-tours.restore')->middleware('bouncer:Transport\TransportInventoryTour,delete');
            });
            Route::prefix('upgrade/{inventoryTour}')->group(function () {
                Route::get('/', [UpgradeController::class, 'viewTransportUpgrade'])->name('transport-upgrade.view')->middleware('bouncer:Transport\TransportInventoryTour,read');
                Route::get('/create', [UpgradeController::class, 'createTransportUpgrade'])->name('transport-upgrade.create')->middleware('bouncer:Transport\TransportInventoryTour,create');
                Route::post('/store', [UpgradeController::class, 'storeTransportUpgrade'])->name('transport-upgrade.store')->middleware('bouncer:Transport\TransportInventoryTour,create');
                Route::prefix('{upgrade}')->group(function () {
                    Route::get('/update', [UpgradeController::class, 'editTransportUpgrade'])->name('transport-upgrade.edit')->middleware('bouncer:Transport\TransportInventoryTour,update');
                    Route::post('/update', [UpgradeController::class, 'updateTransportUpgrade'])->name('transport-upgrade.update')->middleware('bouncer:Transport\TransportInventoryTour,update');
                    Route::post('/delete', [UpgradeController::class, 'deleteTransportUpgrade'])->name('transport-upgrade.delete')->middleware('bouncer:Transport\TransportInventoryTour,delete');
                });
            });
        });
    });
    Route::prefix('payment-installments')->group(function () {
        Route::get('/create', [PaymentInstallmentController::class, 'create'])->name('payment-installments.create')->middleware('bouncer:Tour\Tour,update');
        Route::post('/create', [PaymentInstallmentController::class, 'store'])->name('payment-installments.store')->middleware('bouncer:Tour\Tour,update');
        Route::prefix('{paymentInstallment}')->group(function () {
            Route::get('/', [PaymentInstallmentController::class, 'view'])->name('payment-installments.view')->middleware('bouncer:Tour\Tour,read');
            Route::get('/update', [PaymentInstallmentController::class, 'edit'])->name('payment-installments.edit')->middleware('bouncer:Tour\Tour,update');
            Route::post('/update', [PaymentInstallmentController::class, 'update'])->name('payment-installments.update')->middleware('bouncer:Tour\Tour,update');
            Route::post('/delete', [PaymentInstallmentController::class, 'destroy'])->name('payment-installments.delete')->middleware('bouncer:Tour\Tour,update');
        });
    });
});
Route::prefix('tour-categories')->group(function () {
    Route::get('/', [TourCategoryController::class, 'index'])->name('tour-categories.all')->middleware('bouncer:Tour\TourCategory,read');
    Route::get('/create', [TourCategoryController::class, 'create'])->name('tour-categories.create')->middleware('bouncer:Tour\TourCategory,create');
    Route::post('/create', [TourCategoryController::class, 'store'])->name('tour-categories.store')->middleware('bouncer:Tour\TourCategory,create');

    Route::prefix('{tourCategory}')->group(function () {
        Route::get('/', [TourCategoryController::class, 'view'])->name('tour-categories.view')->middleware('bouncer:Tour\TourCategory,read');
        Route::get('/update', [TourCategoryController::class, 'edit'])->name('tour-categories.edit')->middleware('bouncer:Tour\TourCategory,update');
        Route::post('/update', [TourCategoryController::class, 'update'])->name('tour-categories.update')->middleware('bouncer:Tour\TourCategory,update');
        Route::post('/delete', [TourCategoryController::class, 'destroy'])->name('tour-categories.delete')->middleware('bouncer:Tour\TourCategory,delete');
    });
});
