<?php

use App\Http\Controllers\Customer\BookingV3Controller;
use App\Http\Controllers\Customer\CustomerBookingController;
use App\Http\Controllers\Customer\SimpleBookingController;
use App\Http\Controllers\StripeController;
use App\Http\Gateways\AirwallexGateway;
use App\Http\Gateways\FellohGateway;
use App\Http\Gateways\OpayoGateway;
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

Route::prefix('admin')->group(__DIR__ . '/web/admin.php');

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
        Route::prefix('opayo')->name('opayo.')->group(function () {
            Route::get('failed', [OpayoGateway::class, 'failed'])->name('failed');
        });
        Route::prefix('airwallex')->name('airwallex.')->group(function () {
            Route::get('checkout', [AirwallexGateway::class, 'showCheckout'])->name('checkout');
        });
    });
});

Route::prefix('/booking/simple/{tour}')->group(function () {
    Route::get('/checkout/{token}', [SimpleBookingController::class, 'checkout'])->name('booking.simple.checkout');
    Route::get('/{token?}', [SimpleBookingController::class, 'index'])->name('booking.simple.index');
});

Route::prefix('/booking/v3/{tour}')->group(function () {
    Route::get('/{booking?}', [BookingV3Controller::class, 'guest'])->name('booking.v3.guest');
    Route::get('/hotels/{booking?}', [BookingV3Controller::class, 'hotel'])->name('booking.v3.hotel');
    Route::get('/tickets/{booking?}', [BookingV3Controller::class, 'ticket'])->name('booking.v3.tickets');
    Route::get('/inclusions/{booking?}', [BookingV3Controller::class, 'inclusion'])->name('booking.v3.inclusions');
    Route::get('/details/{booking?}', [BookingV3Controller::class, 'details'])->name('booking.v3.details');
    Route::get('/confirmation/{booking?}', [BookingV3Controller::class, 'confirmation'])->name('booking.v3.confirmation');
    Route::get('/reset/{booking?}', [BookingV3Controller::class, 'confirmation'])->name('booking.v3.confirmation');
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
