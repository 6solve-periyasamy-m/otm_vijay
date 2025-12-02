<?php


use App\Http\Controllers\Api\Customer\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/tour', [BookingController::class, 'overview'])->name('tour');
Route::get('/booking', [BookingController::class, 'booking'])->name('booking');
Route::post('/setup', [BookingController::class, 'setup'])->name('setup');
Route::prefix('stripe')->name('stripe.')->group(function () {
    Route::get('/publishable', [BookingController::class, 'getStripePublishableKey'])->name('publishable');
    Route::get('/secret', [BookingController::class, 'getStripeSecret'])->name('secret');
});