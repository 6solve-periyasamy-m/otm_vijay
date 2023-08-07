<?php

use App\Http\Controllers\Admin\Customer\CustomerController;
use App\Http\Controllers\Admin\Customer\HatSizeController;
use App\Http\Controllers\Admin\Customer\TShirtSizeController;

Route::get('/', [CustomerController::class, 'index'])->name('customers.all')->middleware('bouncer:Customer\Customer,read');
Route::get('/create', [CustomerController::class, 'create'])->name('customers.create')->middleware('bouncer:Customer\Customer,create');
Route::post('/create', [CustomerController::class, 'store'])->name('customers.store')->middleware('bouncer:Customer\Customer,create');
Route::post('/login', [CustomerController::class, 'login'])->name('customers.login-as')->middleware('bouncer:Customer\Customer,read');
Route::prefix('{customer}')->group(function () {
    Route::get('/', [CustomerController::class, 'view'])->name('customers.view')->middleware('bouncer:Customer\Customer,read');
    Route::post('/forget', [CustomerController::class, 'forget'])->name('customers.forget')->middleware(['bouncer:Customer\Customer,update', 'password.confirm']);
    Route::get('/update', [CustomerController::class, 'edit'])->name('customers.edit')->middleware('bouncer:Customer\Customer,update');
    Route::post('/update', [CustomerController::class, 'update'])->name('customers.update')->middleware('bouncer:Customer\Customer,update');
    Route::post('/delete', [CustomerController::class, 'destroy'])->name('customers.delete')->middleware('bouncer:Customer\Customer,delete');
});
Route::prefix('t-shirt-sizes')->group(function () {
    Route::get('/', [TShirtSizeController::class, 'index'])->name('t-shirt-sizes.all')->middleware('bouncer:Customer\TShirtSize,read');
    Route::get('/create', [TShirtSizeController::class, 'create'])->name('t-shirt-sizes.create')->middleware('bouncer:Customer\TShirtSize,create');
    Route::post('/create', [TShirtSizeController::class, 'store'])->name('t-shirt-sizes.store')->middleware('bouncer:Customer\TShirtSize,create');
    Route::prefix('{tShirtSize}')->group(function () {
        Route::get('/', [TShirtSizeController::class, 'view'])->name('t-shirt-sizes.view')->middleware('bouncer:Customer\TShirtSize,read');
        Route::get('/update', [TShirtSizeController::class, 'edit'])->name('t-shirt-sizes.edit')->middleware('bouncer:Customer\TShirtSize,update');
        Route::post('/update', [TShirtSizeController::class, 'update'])->name('t-shirt-sizes.update')->middleware('bouncer:Customer\TShirtSize,update');
        Route::post('/delete', [TShirtSizeController::class, 'destroy'])->name('t-shirt-sizes.delete')->middleware('bouncer:Customer\TShirtSize,delete');
    });
});
Route::prefix('hat-sizes')->group(function () {
    Route::get('/', [HatSizeController::class, 'index'])->name('hat-sizes.all')->middleware('bouncer:Customer\HatSize,read');
    Route::get('/create', [HatSizeController::class, 'create'])->name('hat-sizes.create')->middleware('bouncer:Customer\HatSize,create');
    Route::post('/create', [HatSizeController::class, 'store'])->name('hat-sizes.store')->middleware('bouncer:Customer\HatSize,create');
    Route::prefix('{hatSize}')->group(function () {
        Route::get('/', [HatSizeController::class, 'view'])->name('hat-sizes.view')->middleware('bouncer:Customer\HatSize,read');
        Route::get('/update', [HatSizeController::class, 'edit'])->name('hat-sizes.edit')->middleware('bouncer:Customer\HatSize,update');
        Route::post('/update', [HatSizeController::class, 'update'])->name('hat-sizes.update')->middleware('bouncer:Customer\HatSize,update');
        Route::post('/delete', [HatSizeController::class, 'destroy'])->name('hat-sizes.delete')->middleware('bouncer:Customer\HatSize,delete');
    });
});
