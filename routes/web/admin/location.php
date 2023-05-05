<?php

use App\Http\Controllers\Admin\Location\AddressController;
use App\Http\Controllers\Admin\Location\CountryController;
use App\Http\Controllers\Admin\Location\LocationTypeController;

Route::prefix('addresses')->group(function () {
    Route::get('/', [AddressController::class, 'index'])->name('addresses.all')->middleware('bouncer:Location\Address,read');
    Route::get('/create/{addressParent}', [AddressController::class, 'create'])->name('addresses.create')->middleware('bouncer:Location\Address,create');
    Route::post('/create/{addressParent}', [AddressController::class, 'store'])->name('addresses.store')->middleware('bouncer:Location\Address,create');
    Route::prefix('{address}')->group(function () {
        Route::get('/', [AddressController::class, 'view'])->name('addresses.view')->middleware('bouncer:Location\Address,read');
        Route::get('/update', [AddressController::class, 'edit'])->name('addresses.edit')->middleware('bouncer:Location\Address,update');
        Route::post('/update', [AddressController::class, 'update'])->name('addresses.update')->middleware('bouncer:Location\Address,update');
        Route::post('/delete', [AddressController::class, 'destroy'])->name('addresses.delete')->middleware('bouncer:Location\Address,delete');
    });
});
Route::prefix('countries')->group(function () {
    Route::get('/', [CountryController::class, 'index'])->name('countries.all')->middleware('bouncer:Location\Country,read');
    Route::get('/create', [CountryController::class, 'create'])->name('countries.create')->middleware('bouncer:Location\Country,create');
    Route::post('/create', [CountryController::class, 'store'])->name('countries.store')->middleware('bouncer:Location\Country,create');
    Route::prefix('{country}')->group(function () {
        Route::get('/', [CountryController::class, 'view'])->name('countries.view')->middleware('bouncer:Location\Country,read');
        Route::get('/update', [CountryController::class, 'edit'])->name('countries.edit')->middleware('bouncer:Location\Country,update');
        Route::post('/update', [CountryController::class, 'update'])->name('countries.update')->middleware('bouncer:Location\Country,update');
        Route::post('/delete', [CountryController::class, 'destroy'])->name('countries.delete')->middleware('bouncer:Location\Country,delete');
    });
});
Route::prefix('location-types')->group(function () {
    Route::get('/', [LocationTypeController::class, 'index'])->name('location-types.all')->middleware('bouncer:Location\LocationType,read');
    Route::get('/create', [LocationTypeController::class, 'create'])->name('location-types.create')->middleware('bouncer:Location\LocationType,create');
    Route::post('/create', [LocationTypeController::class, 'store'])->name('location-types.store')->middleware('bouncer:Location\LocationType,create');
    Route::prefix('{locationType}')->group(function () {
        Route::get('/', [LocationTypeController::class, 'view'])->name('location-types.view')->middleware('bouncer:Location\LocationType,read');
        Route::get('/update', [LocationTypeController::class, 'edit'])->name('location-types.edit')->middleware('bouncer:Location\LocationType,update');
        Route::post('/update', [LocationTypeController::class, 'update'])->name('location-types.update')->middleware('bouncer:Location\LocationType,update');
        Route::post('/delete', [LocationTypeController::class, 'destroy'])->name('location-types.delete')->middleware('bouncer:Location\LocationType,delete');
    });
});
