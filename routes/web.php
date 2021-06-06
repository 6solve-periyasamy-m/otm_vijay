<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TourController;
// use App\Http\Controllers\HomeController;
// use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OrderCustomerController;
use App\Http\Controllers\PaymentScheduleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\BookingFormLoginController;

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
    return view('otm');
});

Route::prefix("/booking")->group(function() {

    // debugging routes
    Route::get('/check/events', [TourController::class, 'getEvents']);
    Route::get('/check/tour/{event_id}', [TourController::class, 'getTours']);

    Route::get('/store', function() {
        return view('bookingStore');
    });
    Route::get('/login/{token}', [BookingFormLoginController::class, 'loginWithToken']); // Demo for now
    Route::get('/edit/{id}', [BookingController::class, 'bookingForm']);
    Route::get('/tour/{url}', [BookingController::class, 'bookingForm']);    
    Route::get('/event/{url}', [BookingController::class, 'eventBookingForm']);    
    Route::get('/', [BookingController::class, 'bookingForm']);

    Route::get('/{url}', [BookingController::class, 'tourBookingForm']);

});

Route::get('phones', function() {
    return view('phoneValidation');
});

Route::prefix('customer')->group(function () {
    Route::get('/payment/schedule', [PaymentScheduleController::class, 'index'])->name('payment-schedule');
});

Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::get('/orders-users-components/{id}', [OrderCustomerController::class, 'customerComponents'])->name('customerComponents');
    Route::get('/tour-components/{id}', [TourController::class, 'tourComponents'])->name('tourComponents');
    Route::post('/tour-components/update', [TourController::class, 'tourComponentUpdate'])->name('tourComponentUpdate');
    Route::get('/orders-users-components/{id}', [OrderCustomerController::class, 'customerComponents'])->name('customerComponents');

});
