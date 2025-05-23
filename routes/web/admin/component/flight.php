<?php

use App\Actions\Flight\DeleteFlight;
use App\Http\Controllers\Admin\Flight\AirlineController;
use App\Http\Controllers\Admin\Flight\AirportController;
use App\Http\Controllers\Admin\Flight\FlightController;
use App\Http\Controllers\Admin\Flight\FlightInventoryController;

Route::get('/', [FlightController::class, 'index'])->name('flights.all')->middleware('bouncer:Flight\Flight,read');
Route::get('/create', [FlightController::class, 'create'])->name('flights.create')->middleware('bouncer:Flight\Flight,create');
Route::post('/create', [FlightController::class, 'store'])->name('flights.store')->middleware('bouncer:Flight\Flight,create');
Route::post('/delete', DeleteFlight::class)->name('flights.delete');
Route::prefix('{flight}')->group(function () {
    Route::get('/', [FlightController::class, 'view'])->name('flights.view')->middleware('bouncer:Flight\Flight,read');
    Route::get('/manifest', [FlightController::class, 'manifest'])->name('flights.manifest.view')->middleware('bouncer:Flight\Flight,read');
    Route::get('/manifest/export/{extension?}', [FlightController::class, 'export'])->name('flights.manifest.export')->middleware('bouncer:Flight\Flight,read');
    Route::get('/update', [FlightController::class, 'edit'])->name('flights.edit')->middleware('bouncer:Flight\Flight,update');
    Route::post('/update', [FlightController::class, 'update'])->name('flights.update')->middleware('bouncer:Flight\Flight,update');
    Route::get('/replicate', [FlightController::class, 'createReturn'])->name('flights.return');
    Route::prefix('inventory')->group(function () {
        Route::get('/create', [FlightInventoryController::class, 'create'])->name('flight-inventories.create')->middleware('bouncer:Flight\FlightInventory,create');
        Route::post('/create', [FlightInventoryController::class, 'store'])->name('flight-inventories.store')->middleware('bouncer:Flight\FlightInventory,create');
        Route::prefix('{inventory}')->group(function () {
            Route::get('/manifest', [FlightInventoryController::class, 'manifest'])->name('flight-inventories.manifest.view')->middleware('bouncer:Flight\FlightInventory,read');
            Route::get('/manifest/export/{extension?}', [FlightInventoryController::class, 'export'])->name('flight-inventories.manifest.export')->middleware('bouncer:Flight\FlightInventory,read');
            Route::get('/update', [FlightInventoryController::class, 'edit'])->name('flight-inventories.edit')->middleware('bouncer:Flight\FlightInventory,update');
            Route::post('/update', [FlightInventoryController::class, 'update'])->name('flight-inventories.update')->middleware('bouncer:Flight\FlightInventory,update');
            Route::post('/delete', [FlightInventoryController::class, 'destroy'])->name('flight-inventories.delete')->middleware('bouncer:Flight\FlightInventory,delete');
            Route::get('/duplicate', [FlightInventoryController::class, 'duplicate'])->name('flight-inventories.duplicate')->middleware('bouncer:Flight\FlightInventory,create');
        });
    });
});

Route::prefix('airlines')->group(function () {
    Route::get('/', [AirlineController::class, 'index'])->name('airlines.all')->middleware('bouncer:Flight\Airline,read');
    Route::get('/create', [AirlineController::class, 'create'])->name('airlines.create')->middleware('bouncer:Flight\Airline,create');
    Route::post('/create', [AirlineController::class, 'store'])->name('airlines.store')->middleware('bouncer:Flight\Airline,create');
    Route::prefix('{airline}')->group(function () {
        Route::get('/', [AirlineController::class, 'view'])->name('airlines.view')->middleware('bouncer:Flight\Airline,read');
        Route::get('/update', [AirlineController::class, 'edit'])->name('airlines.edit')->middleware('bouncer:Flight\Airline,update');
        Route::post('/update', [AirlineController::class, 'update'])->name('airlines.update')->middleware('bouncer:Flight\Airline,update');
        Route::post('/delete', [AirlineController::class, 'destroy'])->name('airlines.delete')->middleware('bouncer:Flight\Airline,delete');
    });
});

Route::prefix('airports')->group(function () {
    Route::get('/', [AirportController::class, 'index'])->name('airports.all')->middleware('bouncer:Flight\Airport,read');
    Route::get('/create', [AirportController::class, 'create'])->name('airports.create')->middleware('bouncer:Flight\Airport,create');
    Route::post('/create', [AirportController::class, 'store'])->name('airports.store')->middleware('bouncer:Flight\Airport,create');
    Route::prefix('{airport}')->group(function () {
        Route::get('/', [AirportController::class, 'view'])->name('airports.view')->middleware('bouncer:Flight\Airport,read');
        Route::get('/update', [AirportController::class, 'edit'])->name('airports.edit')->middleware('bouncer:Flight\Airport,update');
        Route::post('/update', [AirportController::class, 'update'])->name('airports.update')->middleware('bouncer:Flight\Airport,update');
        Route::post('/delete', [AirportController::class, 'destroy'])->name('airports.delete')->middleware('bouncer:Flight\Airport,delete');
    });
});
