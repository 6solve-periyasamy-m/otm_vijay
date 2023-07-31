<?php

use App\Http\Controllers\Customer\Auth\CustomerForgotPasswordController;
use App\Http\Controllers\Customer\Auth\CustomerLoginController;
use App\Http\Controllers\Customer\Auth\CustomerRegisterController;
use App\Http\Controllers\Customer\Auth\CustomerResetPasswordController;
use App\Http\Controllers\Customer\CustomerDetailsController;
use App\Http\Controllers\Customer\CustomerFinancesController;
use App\Http\Controllers\Customer\CustomerPortalController;
use App\Http\Controllers\Customer\CustomerTourController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [CustomerLoginController::class, 'show'])->name('login');
Route::get('/register', [CustomerRegisterController::class, 'show'])->name('register');
Route::post('/login', [CustomerLoginController::class, 'login'])->name('confirm-login');
Route::post('/register', [CustomerRegisterController::class, 'register'])->name('confirm-register');

Route::middleware('auth:customer')->group(function () {
    Route::post('/logout', [CustomerLoginController::class, 'logout'])->name('logout');
    Route::get('/atol/{reference}', [CustomerPortalController::class, 'showAtol'])->name('atol');
    Route::get('/portal', [CustomerPortalController::class, 'show'])->name('portal');
    Route::get('/details', [CustomerDetailsController::class, 'edit'])->name('edit');
    Route::post('/details', [CustomerDetailsController::class, 'update'])->name('update');
    Route::get('/details/other/{customer}', [CustomerDetailsController::class, 'editOther'])->name('edit.other');
    Route::post('/details/other/{customer}', [CustomerDetailsController::class, 'updateOther'])->name('update.other');
    Route::get('/finances', [CustomerFinancesController::class, 'show'])->name('finances');
    Route::post('/payment/make', [CustomerFinancesController::class, 'makePayment'])->name('payment.make');
    Route::get('/finances/invoice/{reference}', [CustomerFinancesController::class, 'showInvoice'])->name('invoice');
    Route::get('/itinerary/{reference?}/{customer?}', [CustomerTourController::class, 'showItinerary'])->name('itinerary');
    Route::get('/extras/{reference?}/{customer?}', [CustomerTourController::class, 'showExtras'])->name('extras');
    Route::get('/extras/purchase/{reference}/{componentType}/{componentId}/{customer?}', [CustomerTourController::class, 'purchaseExtra'])->name('extras.purchase');
    Route::get('/extras/apply/{reference}/{componentType}/{componentId}/{customer?}', [CustomerTourController::class, 'addExtra'])->name('extras.apply');
    Route::post('/order/notes/update/{reference}/{orderCustomer}', [CustomerTourController::class, 'updateNotes'])->name('notes.update');
});
Route::prefix('password')->name('password.')->group(function () {
    Route::get('/reset', [CustomerForgotPasswordController::class, 'showLinkRequestForm'])->name('request');
    Route::post('/email', [CustomerForgotPasswordController::class, 'sendResetLinkEmail'])->name('email');
    Route::get('/reset/{token}', [CustomerResetPasswordController::class, 'showResetForm'])->name('reset');
    Route::post('/reset', [CustomerResetPasswordController::class, 'reset'])->name('update');
});
