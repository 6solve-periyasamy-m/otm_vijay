<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TourController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OrderCustomerController;
use App\Http\Controllers\BookingController;

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
    return view('welcome');
});

Route::get('/booking-form', function () {
    return view('bookingForm');
});

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();

    Route::get('/orders-users-components/{id}', [OrderCustomerController::class, 'customerComponents'])->name('customerComponents');
    Route::get('/tour-components/{id}', [TourController::class, 'tourComponents'])->name('tourComponents');
    Route::post('/tour-components/update', [TourController::class, 'tourComponentUpdate'])->name('tourComponentUpdate');
    Route::get('/orders-users-components/{id}', [OrderCustomerController::class, 'customerComponents'])->name('customerComponents');

});

Route::get('booking/{id}', [BookingController::class, 'bookingForm']);
Route::get('booking', [BookingController::class, 'newBookingForm']);

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::get('/orders-users-components/{id}', [OrderCustomerController::class, 'customerComponents'])->name('customerComponents');

});

