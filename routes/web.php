<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TourController;

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
    Route::get('/tour-components/{id}', [TourController::class, 'tourComponents'])->name('tourComponents');
    Route::post('/tour-components/update', [TourController::class, 'tourComponentUpdate'])->name('tourComponentUpdate');
});
