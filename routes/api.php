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

/* valid public routes */
Route::get('/booking/events',            [ApiController::class, 'getEvents']);
Route::get('/booking/tours/{event_id}',  [ApiController::class, 'getTours']);
Route::get('/booking/tour/{id}',         [ApiController::class, 'getBasicTourInformation']);
Route::get('/booking/airlines',          [ApiController::class, 'getAirlines']);
Route::get('/booking/flights/{tour_id}', [ApiController::class, 'getFlightInventoriesForTour']);
Route::get('/booking/flights/{tour_id}/{flight_type}', [ApiController::class, 'getFlightInventoriesForTour']);
Route::get('/booking/flight-inventories',[ApiController::class, 'getFlightsInventories']);

Route::post('/booking/create-order', [ApiController::class, 'createOrder']);
Route::post('/booking/lead-traveller', [ApiController::class, 'leadTraveller']);
Route::post('/booking/additional-traveller', [ApiController::class, 'additionalTraveller']);

Route::get('/booking/flights/airport/{airport}', [ApiController::class, 'getFlightsFromAirport']);
Route::get('/booking/airports',         [ApiController::class, 'getAirports']);

Route::get('/booking/accomodation', [ApiController::class, 'getAccommodationFromTour']);
Route::get('/booking/payment-schedules', [ApiController::class, 'getPaymentSchedules']);
Route::get('/booking/payment-schedule/{id}', [ApiController::class, 'getPaymentSchedule']);

Route::middleware('auth:api')->group(function() {
    
});
