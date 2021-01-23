<?php

use Illuminate\Support\Facades\Route;
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

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::get('/orders-users-components/{id}', [OrderCustomerController::class, 'customerComponents'])->name('customerComponents');
});

Route::get('booking', [BookingController::class, 'bookingForm']);
Route::post('booking', [BookingController::class, 'bookingForm']);

