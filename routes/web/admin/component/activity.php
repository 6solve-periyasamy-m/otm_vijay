<?php

use App\Actions\Activity\DeleteActivity;
use App\Http\Controllers\Admin\Activity\ActivityController;
use App\Http\Controllers\Admin\Activity\ActivityInventoryController;
use App\Http\Controllers\Admin\Activity\ActivityTypeController;
use App\Http\Controllers\Admin\Activity\TicketTypeController;

Route::get('/', [ActivityController::class, 'index'])->name('activities.all')->middleware('bouncer:Activity\Activity,read');
Route::get('/create', [ActivityController::class, 'create'])->name('activities.create')->middleware('bouncer:Activity\Activity,create');
Route::post('/create', [ActivityController::class, 'store'])->name('activities.store')->middleware('bouncer:Activity\Activity,create');
Route::post('/delete', DeleteActivity::class)->name('activities.delete');
Route::prefix('{activity}')->group(function () {
    Route::get('/', [ActivityController::class, 'view'])->name('activities.view')->middleware('bouncer:Activity\Activity,read');
    Route::get('/manifest', [ActivityController::class, 'manifest'])->name('activities.manifest.view')->middleware('bouncer:Activity\Activity,read');
    Route::get('/manifest/export/{extension?}', [ActivityController::class, 'export'])->name('activities.manifest.export')->middleware('bouncer:Activity\Activity,read');
    Route::get('/update', [ActivityController::class, 'edit'])->name('activities.edit')->middleware('bouncer:Activity\Activity,update');
    Route::post('/update', [ActivityController::class, 'update'])->name('activities.update')->middleware('bouncer:Activity\Activity,update');
    Route::get('/archive', [ActivityController::class, 'archive'])->name('activities.archive')->middleware('bouncer:Activity\Activity,delete');
    Route::prefix('inventory')->group(function () {
        Route::get('/create', [ActivityInventoryController::class, 'create'])->name('activity-inventories.create')->middleware('bouncer:Activity\ActivityInventory,create');
        Route::post('/create', [ActivityInventoryController::class, 'store'])->name('activity-inventories.store')->middleware('bouncer:Activity\ActivityInventory,create');
        Route::prefix('{inventory}')->group(function () {
            Route::get('/manifest', [ActivityInventoryController::class, 'manifest'])->name('activity-inventories.manifest.view')->middleware('bouncer:Activity\ActivityInventory,read');
            Route::get('/manifest/export/{extension?}', [ActivityInventoryController::class, 'export'])->name('activity-inventories.manifest.export')->middleware('bouncer:Activity\ActivityInventory,read');
            Route::get('/update', [ActivityInventoryController::class, 'edit'])->name('activity-inventories.edit')->middleware('bouncer:Activity\ActivityInventory,update');
            Route::post('/update', [ActivityInventoryController::class, 'update'])->name('activity-inventories.update')->middleware('bouncer:Activity\ActivityInventory,update');
            Route::post('/delete', [ActivityInventoryController::class, 'destroy'])->name('activity-inventories.delete')->middleware('bouncer:Activity\ActivityInventory,delete');
            Route::get('/duplicate', [ActivityInventoryController::class, 'duplicate'])->name('activity-inventories.duplicate')->middleware('bouncer:Activity\ActivityInventory,create');
        });
    });
});
Route::prefix('activity-types')->group(function () {
    Route::get('/create', [ActivityTypeController::class, 'create'])->name('activity-types.create')->middleware('bouncer:Activity\ActivityType,create');
    Route::post('/create', [ActivityTypeController::class, 'store'])->name('activity-types.store')->middleware('bouncer:Activity\ActivityType,create');
    Route::prefix('{activityType}')->group(function () {
        Route::get('/update', [ActivityTypeController::class, 'edit'])->name('activity-types.edit')->middleware('bouncer:Activity\ActivityType,update');
        Route::post('/update', [ActivityTypeController::class, 'update'])->name('activity-types.update')->middleware('bouncer:Activity\ActivityType,update');
        Route::post('/delete', [ActivityTypeController::class, 'destroy'])->name('activity-types.delete')->middleware('bouncer:Activity\ActivityType,delete');
    });
});
Route::prefix('ticket-types')->group(function () {
    Route::get('/create', [TicketTypeController::class, 'create'])->name('ticket-types.create')->middleware('bouncer:Activity\TicketType,create');
    Route::post('/create', [TicketTypeController::class, 'store'])->name('ticket-types.store')->middleware('bouncer:Activity\TicketType,create');
    Route::prefix('{ticketType}')->group(function () {
        Route::get('/update', [TicketTypeController::class, 'edit'])->name('ticket-types.edit')->middleware('bouncer:Activity\TicketType,update');
        Route::post('/update', [TicketTypeController::class, 'update'])->name('ticket-types.update')->middleware('bouncer:Activity\TicketType,update');
        Route::post('/delete', [TicketTypeController::class, 'destroy'])->name('ticket-types.delete')->middleware('bouncer:Activity\TicketType,delete');
    });
});
