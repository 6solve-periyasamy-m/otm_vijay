<?php


use App\Http\Controllers\Api\Customer\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('tour', [BookingController::class, 'overview'])->name('tour');
Route::get('booking', [BookingController::class, 'booking'])->name('booking');
Route::post('setup', [BookingController::class, 'setup'])->name('setup');