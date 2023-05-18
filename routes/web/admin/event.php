<?php

use App\Http\Controllers\Admin\Tour\EventController;

Route::get('/', [EventController::class, 'index'])->name('events.all')->middleware('bouncer:Tour\Event,read');
Route::get('/create', [EventController::class, 'create'])->name('events.create')->middleware('bouncer:Tour\Event,create');
Route::post('/create', [EventController::class, 'store'])->name('events.store')->middleware('bouncer:Tour\Event,create');
Route::prefix('{event}')->group(function () {
    Route::get('/', [EventController::class, 'view'])->name('events.view')->middleware('bouncer:Tour\Event,read');
    Route::get('/update', [EventController::class, 'edit'])->name('events.edit')->middleware('bouncer:Tour\Event,update');
    Route::post('/update', [EventController::class, 'update'])->name('events.update')->middleware('bouncer:Tour\Event,update');
    Route::post('/delete', [EventController::class, 'destroy'])->name('events.delete')->middleware('bouncer:Tour\Event,delete');
});
