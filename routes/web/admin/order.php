<?php

use App\Http\Controllers\Admin\Order\Adjustment\ManualAdjustmentController;
use App\Http\Controllers\Admin\Order\Adjustment\OrderCustomerAdjustmentController;
use App\Http\Controllers\Admin\Order\OrderComponentController;
use App\Http\Controllers\Admin\Order\OrderController;
use App\Http\Controllers\Admin\Order\OrderCustomerModelController;
use App\Http\Controllers\Admin\Order\OrderInstallmentController;
use App\Http\Controllers\Admin\Order\Payment\PaymentController;
use App\Http\Controllers\Admin\Order\Payment\PaymentMethodController;
use App\Http\Controllers\Admin\System\SettingsController;

Route::get('/', [OrderController::class, 'index'])->name("orders.all")->middleware('bouncer:Order\Order,read');
Route::get('/create', [OrderController::class, 'create'])->name('orders.create')->middleware('bouncer:Order\Order,create');
Route::post('/create', [OrderController::class, 'store'])->name('orders.store')->middleware('bouncer:Order\Order,create');
Route::get('reminders/authorize/{days}', [SettingsController::class, 'authorizeReminders'])->name('orders.reminders.authorize')->middleware('bouncer:Order\Order,update');
Route::get('reminders/{max?}/{min?}', [OrderController::class, 'reminders'])->name('orders.reminders')->middleware('bouncer:Order\Order,read');
Route::prefix('{order}')->group(function () {
    Route::get('/', [OrderController::class, 'view'])->name("orders.view")->middleware('bouncer:Order\Order,read');
    Route::get('/update/', [OrderController::class, 'edit'])->name('orders.edit')->middleware('bouncer:Order\Order,update');
    Route::post('/update/', [OrderController::class, 'update'])->name('orders.update')->middleware('bouncer:Order\Order,update');
    Route::post('/delete/', [OrderController::class, 'destroy'])->name('orders.delete')->middleware('bouncer:Order\Order,delete');
    Route::post('/delete/force', [OrderController::class, 'forceDelete'])->name('orders.delete.force')->middleware(['bouncer:Order\Order,delete', 'auth.otm']);
    Route::post('/restore/', [OrderController::class, 'restore'])->name('orders.restore')->middleware('bouncer:Order\Order,delete');
    Route::get('/invoice', [OrderController::class, 'invoice'])->name('orders.invoice.latest')->middleware('bouncer:Order\Order,read');
    Route::get('/atol', [OrderController::class, 'atol'])->name('orders.atol')->middleware('bouncer:Order\Order,read');
    Route::get('/occupancy', [OrderController::class, 'occupancy'])->name('orders.occupancy')->middleware('bouncer:Order\Order,update');
    Route::get('/migrate', [OrderController::class, 'switchTour'])->name('orders.switch')->middleware('bouncer:Order\Order,update');
    Route::post('/migrate', [OrderController::class, 'migrate'])->name('orders.migrate')->middleware('bouncer:Order\Order,update');
    Route::prefix('installments')->group(function () {
        Route::get('/create', [OrderInstallmentController::class, 'create'])->name('order-installments.create')->middleware('bouncer:Order\Order,update');
        Route::post('/create', [OrderInstallmentController::class, 'store'])->name('order-installments.store')->middleware('bouncer:Order\Order,update');
        Route::get('/resync', [OrderInstallmentController::class, 'resync'])->name('order-installments.resync')->middleware('bouncer:Order\Order,update');
        Route::prefix('{orderInstallment}')->group(function () {
            Route::get('/update', [OrderInstallmentController::class, 'edit'])->name('order-installments.edit')->middleware('bouncer:Order\Order,update');
            Route::post('/update', [OrderInstallmentController::class, 'update'])->name('order-installments.update')->middleware('bouncer:Order\Order,update');
            Route::post('/delete', [OrderInstallmentController::class, 'destroy'])->name('order-installments.delete')->middleware('bouncer:Order\Order,update');
        });
    });

    Route::prefix('adjustments')->group(function () {
        Route::get('/', [ManualAdjustmentController::class, 'index'])->name('manual-adjustments.all')->middleware('bouncer:Order\Adjustment\ManualAdjustment,read');
        Route::get('/create', [ManualAdjustmentController::class, 'create'])->name('manual-adjustments.create')->middleware('bouncer:Order\Adjustment\ManualAdjustment,create');
        Route::post('/create', [ManualAdjustmentController::class, 'store'])->name('manual-adjustments.store')->middleware('bouncer:Order\Adjustment\ManualAdjustment,create');

        Route::prefix('{manualAdjustment}')->group(function () {
            Route::get('/', [ManualAdjustmentController::class, 'view'])->name('manual-adjustments.view')->middleware('bouncer:Order\Adjustment\ManualAdjustment,read');
            Route::get('/update', [ManualAdjustmentController::class, 'edit'])->name('manual-adjustments.edit')->middleware('bouncer:Order\Adjustment\ManualAdjustment,update');
            Route::post('/update', [ManualAdjustmentController::class, 'update'])->name('manual-adjustments.update')->middleware('bouncer:Order\Adjustment\ManualAdjustment,update');
            Route::post('/delete', [ManualAdjustmentController::class, 'destroy'])->name('manual-adjustments.delete')->middleware('bouncer:Order\Adjustment\ManualAdjustment,delete');
        });
    });

    Route::prefix('customer')->group(function () {
        Route::get('/', [OrderCustomerModelController::class, 'index'])->name('order-customers.all')->middleware('bouncer:Order\OrderCustomer,read');
        Route::get('/create', [OrderCustomerModelController::class, 'create'])->name('order-customers.create')->middleware('bouncer:Order\OrderCustomer,create');
        Route::post('/create', [OrderCustomerModelController::class, 'store'])->name('order-customers.store')->middleware('bouncer:Order\OrderCustomer,create');

        Route::prefix('{orderCustomer}')->group(function () {
            Route::get('/', [OrderCustomerModelController::class, 'show'])->name("order-customers.view")->middleware('bouncer:Order\OrderCustomer,read');
            Route::get('/update', [OrderCustomerModelController::class, 'edit'])->name('order-customers.edit')->middleware('bouncer:Order\OrderCustomer,update');
            Route::post('/update', [OrderCustomerModelController::class, 'update'])->name('order-customers.update')->middleware('bouncer:Order\OrderCustomer,update');
            Route::post('/delete', [OrderCustomerModelController::class, 'destroy'])->name('order-customers.delete')->middleware('bouncer:Order\OrderCustomer,delete');
            Route::get('/merchandise/{orderMerchandise}/fulfil', [OrderCustomerModelController::class, 'fulfil'])->name('merchandise.inventory.tour.order.fulfil')->middleware('bouncer:Order\OrderCustomer,update');
            Route::prefix('adjustment')->group(function () {
                Route::get('/', [OrderCustomerAdjustmentController::class, 'index'])->name('order-customer-adjustments.all')->middleware('bouncer:Order\Adjustment\OrderCustomerAdjustment,read');
                Route::get('/create', [OrderCustomerAdjustmentController::class, 'create'])->name('order-customer-adjustments.create')->middleware('bouncer:Order\Adjustment\OrderCustomerAdjustment,create');
                Route::post('/create', [OrderCustomerAdjustmentController::class, 'store'])->name('order-customer-adjustments.store')->middleware('bouncer:Order\Adjustment\OrderCustomerAdjustment,create');

                Route::prefix('{orderCustomerAdjustment}')->group(function () {
                    Route::get('/', [OrderCustomerAdjustmentController::class, 'view'])->name('order-customer-adjustments.view')->middleware('bouncer:Order\Adjustment\OrderCustomerAdjustment,read');
                    Route::get('/update', [OrderCustomerAdjustmentController::class, 'edit'])->name('order-customer-adjustments.edit')->middleware('bouncer:Order\Adjustment\OrderCustomerAdjustment,update');
                    Route::post('/update', [OrderCustomerAdjustmentController::class, 'update'])->name('order-customer-adjustments.update')->middleware('bouncer:Order\Adjustment\OrderCustomerAdjustment,update');
                    Route::post('/delete', [OrderCustomerAdjustmentController::class, 'destroy'])->name('order-customer-adjustments.delete')->middleware('bouncer:Order\Adjustment\OrderCustomerAdjustment,delete');
                });
            });
        });
    });

    Route::prefix('payments')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('payments.all')->middleware('bouncer:Order\Payment\Payment,read');
        Route::get('/create', [PaymentController::class, 'create'])->name('payments.create')->middleware('bouncer:Order\Payment\Payment,create');
        Route::post('/create', [PaymentController::class, 'store'])->name('payments.store')->middleware('bouncer:Order\Payment\Payment,create');

        Route::prefix('{payment}')->group(function () {
            Route::get('/', [PaymentController::class, 'view'])->name('payments.view')->middleware('bouncer:Order\Payment\Payment,read');
            Route::get('/update', [PaymentController::class, 'edit'])->name('payments.edit')->middleware('bouncer:Order\Payment\Payment,update');
            Route::post('/update', [PaymentController::class, 'update'])->name('payments.update')->middleware('bouncer:Order\Payment\Payment,update');
            Route::post('/delete', [PaymentController::class, 'destroy'])->name('payments.delete')->middleware('bouncer:Order\Payment\Payment,delete');
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
    Route::post('accommodation/{id}/delete', [OrderComponentController::class, 'deleteAccommodation'])->name('orderAccommodationDelete')->middleware('bouncer:Order\OrderCustomer,update');
    Route::post('activity/{id}/delete', [OrderComponentController::class, 'deleteActivity'])->name('orderActivityDelete')->middleware('bouncer:Order\OrderCustomer,update');
    Route::post('flight/{id}/delete', [OrderComponentController::class, 'deleteFlight'])->name('orderFlightDelete')->middleware('bouncer:Order\OrderCustomer,update');
    Route::post('transport/{id}/delete', [OrderComponentController::class, 'deleteTransport'])->name('orderTransportDelete')->middleware('bouncer:Order\OrderCustomer,update');
    Route::post('merchandise/{id}/delete', [OrderComponentController::class, 'deleteMerchandise'])->name('orderMerchandiseDelete')->middleware('bouncer:Order\OrderCustomer,update');
});
