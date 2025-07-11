<?php

use App\Actions\Merchandise\DeleteMerchandise;
use App\Http\Controllers\Admin\Merchandise\MerchandiseController;
use App\Http\Controllers\Admin\Merchandise\MerchandiseInventoryController;
use App\Http\Controllers\Admin\Merchandise\MerchandiseSizeController;
use App\Http\Controllers\Admin\Merchandise\MerchandiseTypeController;
use App\Http\Controllers\Admin\Merchandise\VariantController;

Route::get('/', [MerchandiseController::class, 'index'])->name('all');
Route::get('/create', [MerchandiseController::class, 'create'])->name('create');
Route::post('/create', [MerchandiseController::class, 'store'])->name('store');
Route::post('/delete', DeleteMerchandise::class)->name('delete');
Route::prefix('{merchandise}')->group(function () {
    Route::get('/', [MerchandiseController::class, 'show'])->name('view');
    Route::get('/detailed', [MerchandiseController::class, 'detailed'])->name('detailed');
    Route::get('/update/{view?}', [MerchandiseController::class, 'edit'])->name('edit');
    Route::post('/update/{view?}', [MerchandiseController::class, 'update'])->name('update');
    Route::get('/archive', [MerchandiseController::class, 'archive'])->name('archive')->middleware('bouncer:Merchandise\Merchandise,delete');
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/create/{view?}', [MerchandiseInventoryController::class, 'create'])->name('create');
        Route::post('/create/{view?}', [MerchandiseInventoryController::class, 'store'])->name('store');
        Route::prefix('{inventory}')->group(function () {
            Route::get('/update/{view?}', [MerchandiseInventoryController::class, 'edit'])->name('edit');
            Route::post('/update/{view?}', [MerchandiseInventoryController::class, 'update'])->name('update');
            Route::post('/delete/{view?}', [MerchandiseInventoryController::class, 'delete'])->name('delete');
            Route::get('/duplicate/{view?}', [MerchandiseInventoryController::class, 'duplicate'])->name('duplicate');
        });
    });
});
Route::prefix('type')->name('type.')->group(function () {
    Route::get('/create', [MerchandiseTypeController::class, 'create'])->name('create');
    Route::post('/create', [MerchandiseTypeController::class, 'store'])->name('store');
    Route::prefix('{type}')->group(function () {
        Route::get('/update', [MerchandiseTypeController::class, 'edit'])->name('edit');
        Route::post('/update', [MerchandiseTypeController::class, 'update'])->name('update');
        Route::post('/delete', [MerchandiseTypeController::class, 'delete'])->name('delete');
    });
});
Route::prefix('size')->name('size.')->group(function () {
    Route::get('/create', [MerchandiseSizeController::class, 'create'])->name('create');
    Route::post('/create', [MerchandiseSizeController::class, 'store'])->name('store');
    Route::prefix('{size}')->group(function () {
        Route::get('/update', [MerchandiseSizeController::class, 'edit'])->name('edit');
        Route::post('/update', [MerchandiseSizeController::class, 'update'])->name('update');
        Route::post('/delete', [MerchandiseSizeController::class, 'delete'])->name('delete');
    });
});
Route::prefix('variant')->name('variant.')->group(function () {
    Route::get('/create', [VariantController::class, 'create'])->name('create');
    Route::post('/create', [VariantController::class, 'store'])->name('store');
    Route::prefix('{type}')->group(function () {
        Route::get('/update', [VariantController::class, 'edit'])->name('edit');
        Route::post('/update', [VariantController::class, 'update'])->name('update');
        Route::post('/delete', [VariantController::class, 'delete'])->name('delete');
    });
});
