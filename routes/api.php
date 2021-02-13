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

Route::middleware('auth:api')->get('/booking/flight', [ApiController::class, 'getFlightsFromTour']);
Route::middleware('auth:api')->get('/booking/tour/{id}', [ApiController::class, 'getBasicTourInformation']);
Route::middleware('auth:api')->get('/booking/accomodation', [ApiController::class, 'getAccommodationFromTour']);


Route::prefix('/booking')->group(function() {
    Route::post('/store', [BookingController::class, 'store']);
    // Route::put('{id}', [BookingController::class, 'update']);
    // Route::delete('{id}', [BookingController::class, 'destroy']);
});
