<?php

use App\Http\Controllers\Admin\Tour\EventController;

Route::get('/', [EventController::class, 'index'])->name('all')->middleware('bouncer:Tour\Event,read');
Route::get('/create', [EventController::class, 'create'])->name('create')->middleware('bouncer:Tour\Event,create');
Route::post('/create', [EventController::class, 'store'])->name('store')->middleware('bouncer:Tour\Event,create');
Route::prefix('{event}')->group(function () {
    Route::get('/', [EventController::class, 'view'])->name('view')->middleware('bouncer:Tour\Event,read');
    Route::get('/remind/bulk', [EventController::class, 'bulkRemind'])->name('reminder.bulk')->middleware('bouncer:Tour\Event,read');
    Route::get('/remind/bulk/export/{extension}', [EventController::class, 'bulkRemindExport'])->name('reminder.bulk.export')->middleware('bouncer:Tour\Event,read');
    Route::get('/update', [EventController::class, 'edit'])->name('edit')->middleware('bouncer:Tour\Event,update');
    Route::post('/update', [EventController::class, 'update'])->name('update')->middleware('bouncer:Tour\Event,update');
    Route::post('/delete', [EventController::class, 'destroy'])->name('delete')->middleware('bouncer:Tour\Event,delete');
    Route::prefix('/manifest')->name('manifest.')->group(function () {
        Route::prefix('/order')->name('order.')->group(function () {
            Route::get('/', [EventController::class, 'orderManifest'])->name('view');
            Route::get('/export/{extension?}', [EventController::class, 'exportOrderManifest'])->name('export');
        });
    });
});
