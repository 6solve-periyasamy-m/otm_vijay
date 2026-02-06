<?php


use App\Http\Controllers\Api\Customer\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/tour', [BookingController::class, 'overview'])->name('tour');
Route::get('/booking', [BookingController::class, 'booking'])->name('booking');
Route::post('/setup', [BookingController::class, 'setup'])->name('setup');
Route::post('/rooming', [BookingController::class, 'processRooming'])->name('rooming');
Route::post('/component', [BookingController::class, 'processComponents'])->name('component');
Route::post('/details', [BookingController::class, 'processDetails'])->name('details');
Route::prefix('traveller')->name('traveller.')->group(function () {
   Route::post('/add', [BookingController::class, 'addTraveller'])->name('add');
   Route::post('/set', [BookingController::class, 'setTravellers'])->name('set');
   Route::post('/remove', [BookingController::class, 'removeTraveller'])->name('remove');
});
Route::prefix('stripe')->name('stripe.')->group(function () {
    Route::get('/publishable', [BookingController::class, 'getStripePublishableKey'])->name('publishable');
    Route::get('/secret', [BookingController::class, 'getStripeSecret'])->name('secret');
});
Route::prefix('order')->name('order.')->group(function () {
    Route::post('/create', [BookingController::class, 'createOrder'])->name('create');
    Route::post('/{order}/update-payment', [BookingController::class, 'updateOrderPayment'])->name('update-payment');
});