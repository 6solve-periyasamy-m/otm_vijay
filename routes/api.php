<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/tours', [ApiController::class, 'getTours']);
Route::get('/airlines', [ApiController::class, 'getAirlines']);
Route::get('/flights/airport/{airport}', [ApiController::class, 'getFlightsFromAirport']);
Route::get('/booking/flight',       [ApiController::class, 'getFlightsFromTour']);
Route::get('/booking/accomodation', [ApiController::class, 'getAccommodationFromTour']);
Route::get('/booking/accomodation', [ApiController::class, 'getAccommodationFromTour']);
Route::get('/booking/accomodation', [ApiController::class, 'getAccommodationFromTour']);
Route::get('/booking/payment-schedules/{tour_id}', [ApiController::class, 'getPaymentSchedules']);
Route::get('/booking/payment-schedules', [ApiController::class, 'getPaymentSchedules']);
Route::get('/booking/payment-installments/{schedule_id}', [ApiController::class, 'getPaymentInstallments']);

Route::middleware('auth:api')->group(function() {
    Route::get('/booking/tour/{id}', [ApiController::class, 'getBasicTourInformation']);
});
