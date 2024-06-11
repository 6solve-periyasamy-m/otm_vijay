<?php


use App\Http\Controllers\Admin\Accommodation\AccommodationController;
use App\Http\Controllers\Admin\Accommodation\AccommodationInventoryController;
use App\Http\Controllers\Admin\Accommodation\BoardTypeController;
use App\Http\Controllers\Admin\Accommodation\RoomTypeController;

Route::get('/', [AccommodationController::class, 'index'])->name('accommodations.all')->middleware('bouncer:Accommodation\Accommodation,read');
Route::get('/create', [AccommodationController::class, 'create'])->name('accommodations.create')->middleware('bouncer:Accommodation\Accommodation,create');
Route::post('/create', [AccommodationController::class, 'store'])->name('accommodations.store')->middleware('bouncer:Accommodation\Accommodation,create');
Route::get('/identifiers', [AccommodationInventoryController::class, 'exportIdentifier'])->name('accommodation-inventories.identifiers')->middleware('bouncer:Accommodation\Accommodation,read');

Route::prefix('{accommodation}')->group(function () {
    Route::get('/', [AccommodationController::class, 'view'])->name('accommodations.view')->middleware('bouncer:Accommodation\Accommodation,read');
    Route::get('/update', [AccommodationController::class, 'edit'])->name('accommodations.edit')->middleware('bouncer:Accommodation\Accommodation,update');
    Route::post('/update', [AccommodationController::class, 'update'])->name('accommodations.update')->middleware('bouncer:Accommodation\Accommodation,update');
    Route::post('/delete', [AccommodationController::class, 'destroy'])->name('accommodations.delete')->middleware('bouncer:Accommodation\Accommodation,delete');
    Route::get('/rooming', [AccommodationController::class, 'rooming'])->name('accommodations.rooming')->middleware('bouncer:Accommodation\Accommodation,read');
    Route::get('/rooming/{extension}', [AccommodationController::class, 'exportRooming'])->name('accommodations.rooming.export')->middleware('bouncer:Accommodation\Accommodation,read');

    Route::prefix('inventory')->group(function () {
        Route::get('/create', [AccommodationInventoryController::class, 'create'])->name('accommodation-inventories.create')->middleware('bouncer:Accommodation\AccommodationInventory,create');
        Route::post('/create', [AccommodationInventoryController::class, 'store'])->name('accommodation-inventories.store')->middleware('bouncer:Accommodation\AccommodationInventory,create');
        Route::prefix('{inventory}')->group(function () {
            Route::get('/update', [AccommodationInventoryController::class, 'edit'])->name('accommodation-inventories.edit')->middleware('bouncer:Accommodation\AccommodationInventory,update');
            Route::post('/update', [AccommodationInventoryController::class, 'update'])->name('accommodation-inventories.update')->middleware('bouncer:Accommodation\AccommodationInventory,update');
            Route::post('/delete', [AccommodationInventoryController::class, 'destroy'])->name('accommodation-inventories.delete')->middleware('bouncer:Accommodation\AccommodationInventory,delete');
            Route::get('/duplicate', [AccommodationInventoryController::class, 'duplicate'])->name('accommodation-inventories.duplicate')->middleware('bouncer:Accommodation\AccommodationInventory,create');
            Route::get('/rooming', [AccommodationInventoryController::class, 'rooming'])->name('accommodation-inventories.rooming')->middleware('bouncer:Accommodation\AccommodationInventory,read');
            Route::get('/rooming/{extension}', [AccommodationInventoryController::class, 'exportRooming'])->name('accommodation-inventories.rooming.export')->middleware('bouncer:Accommodation\AccommodationInventory,read');
        });

    });
});
Route::prefix('room-types')->group(function () {
    Route::get('/create', [RoomTypeController::class, 'create'])->name('room-types.create')->middleware('bouncer:Accommodation\RoomType,create');
    Route::post('/create', [RoomTypeController::class, 'store'])->name('room-types.store')->middleware('bouncer:Accommodation\RoomType,create');
    Route::prefix('{roomType}')->group(function () {
        Route::get('/update', [RoomTypeController::class, 'edit'])->name('room-types.edit')->middleware('bouncer:Accommodation\RoomType,update');
        Route::post('/update', [RoomTypeController::class, 'update'])->name('room-types.update')->middleware('bouncer:Accommodation\RoomType,update');
        Route::post('/delete', [RoomTypeController::class, 'destroy'])->name('room-types.delete')->middleware('bouncer:Accommodation\RoomType,delete');
    });
});
Route::prefix('board-types')->group(function () {
    Route::get('/create', [BoardTypeController::class, 'create'])->name('board-types.create')->middleware('bouncer:Accommodation\BoardType,create');
    Route::post('/create', [BoardTypeController::class, 'store'])->name('board-types.store')->middleware('bouncer:Accommodation\BoardType,create');
    Route::prefix('{boardType}')->group(function () {
        Route::get('/update', [BoardTypeController::class, 'edit'])->name('board-types.edit')->middleware('bouncer:Accommodation\BoardType,update');
        Route::post('/update', [BoardTypeController::class, 'update'])->name('board-types.update')->middleware('bouncer:Accommodation\BoardType,update');
        Route::post('/delete', [BoardTypeController::class, 'destroy'])->name('board-types.delete')->middleware('bouncer:Accommodation\BoardType,delete');
    });
});
