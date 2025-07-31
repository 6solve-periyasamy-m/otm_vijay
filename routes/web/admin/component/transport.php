<?php


use App\Actions\Transport\DeleteTransport;
use App\Http\Controllers\Admin\Transport\OperatorController;
use App\Http\Controllers\Admin\Transport\TransportController;
use App\Http\Controllers\Admin\Transport\TransportInventoryController;
use App\Http\Controllers\Admin\Transport\TransportTypeController;
use App\Http\Controllers\Admin\Transport\TransportOccupancyController;

Route::get('/', [TransportController::class, 'index'])->name('transports.all')->middleware('bouncer:Transport\Transport,read');
Route::get('/create', [TransportController::class, 'create'])->name('transports.create')->middleware('bouncer:Transport\Transport,create');
Route::post('/create', [TransportController::class, 'store'])->name('transports.store')->middleware('bouncer:Transport\Transport,create');
Route::post('/delete', DeleteTransport::class)->name('transports.delete');
Route::prefix('{transport}')->group(function () {
    Route::get('/', [TransportController::class, 'view'])->name('transports.view')->middleware('bouncer:Transport\Transport,read');
    Route::get('/manifest', [TransportController::class, 'manifest'])->name('transports.manifest.view')->middleware('bouncer:Transport\Transport,read');
    Route::get('/manifest/export/{extension?}', [TransportController::class, 'export'])->name('transports.manifest.export')->middleware('bouncer:Transport\Transport,read');
    Route::get('/update', [TransportController::class, 'duplicate'])->name('transports.duplicate')->middleware('bouncer:Transport\Transport,create');
    Route::get('/update', [TransportController::class, 'edit'])->name('transports.edit')->middleware('bouncer:Transport\Transport,update');
    Route::post('/update', [TransportController::class, 'update'])->name('transports.update')->middleware('bouncer:Transport\Transport,update');
    Route::get('/archive', [TransportController::class, 'archive'])->name('transports.archive')->middleware('bouncer:Transport\Transport,delete');
    Route::get('/replicate', [TransportController::class, 'createReturn'])->name('transports.return');
    Route::get('/duplicate', [TransportController::class, 'duplicate'])->name('transports.duplicate')->middleware('bouncer:Transport\Transport,create');
    Route::prefix('inventory')->group(function () {
        Route::get('/create', [TransportInventoryController::class, 'create'])->name('transport-inventories.create')->middleware('bouncer:Transport\TransportInventory,create');
        Route::post('/create', [TransportInventoryController::class, 'store'])->name('transport-inventories.store')->middleware('bouncer:Transport\TransportInventory,create');
        Route::prefix('{inventory}')->group(function () {
            Route::get('/manifest', [TransportInventoryController::class, 'manifest'])->name('transport-inventories.manifest.view')->middleware('bouncer:Transport\TransportInventory,read');
            Route::get('/manifest/export/{extension?}', [TransportInventoryController::class, 'export'])->name('transport-inventories.manifest.export')->middleware('bouncer:Transport\TransportInventory,read');
            Route::get('/update', [TransportInventoryController::class, 'edit'])->name('transport-inventories.edit')->middleware('bouncer:Transport\TransportInventory,update');
            Route::post('/update', [TransportInventoryController::class, 'update'])->name('transport-inventories.update')->middleware('bouncer:Transport\TransportInventory,update');
            Route::post('/delete', [TransportInventoryController::class, 'destroy'])->name('transport-inventories.delete')->middleware('bouncer:Transport\TransportInventory,delete');
            Route::get('/duplicate', [TransportInventoryController::class, 'duplicate'])->name('transport-inventories.duplicate')->middleware('bouncer:Transport\TransportInventory,create');
        });
    });
});
Route::prefix('operators')->group(function () {
    Route::get('/', [OperatorController::class, 'index'])->name('operators.all')->middleware('bouncer:Transport\Operator,read');
    Route::get('/create', [OperatorController::class, 'create'])->name('operators.create')->middleware('bouncer:Transport\Operator,create');
    Route::post('/create', [OperatorController::class, 'store'])->name('operators.store')->middleware('bouncer:Transport\Operator,create');
    Route::prefix('{operator}')->group(function () {
        Route::get('/', [OperatorController::class, 'view'])->name('operators.view')->middleware('bouncer:Transport\Operator,read');
        Route::get('/update', [OperatorController::class, 'edit'])->name('operators.edit')->middleware('bouncer:Transport\Operator,update');
        Route::post('/update', [OperatorController::class, 'update'])->name('operators.update')->middleware('bouncer:Transport\Operator,update');
        Route::post('/delete', [OperatorController::class, 'destroy'])->name('operators.delete')->middleware('bouncer:Transport\Operator,delete');

    });
});
Route::prefix('transport-types')->group(function () {
    Route::get('/', [TransportTypeController::class, 'index'])->name('transport-types.all')->middleware('bouncer:Transport\TransportType,read');
    Route::get('/create', [TransportTypeController::class, 'create'])->name('transport-types.create')->middleware('bouncer:Transport\TransportType,create');
    Route::post('/create', [TransportTypeController::class, 'store'])->name('transport-types.store')->middleware('bouncer:Transport\TransportType,create');
    Route::prefix('{transportType}')->group(function () {
        Route::get('/', [TransportTypeController::class, 'view'])->name('transport-types.view')->middleware('bouncer:Transport\TransportType,read');
        Route::get('/update', [TransportTypeController::class, 'edit'])->name('transport-types.edit')->middleware('bouncer:Transport\TransportType,update');
        Route::post('/update', [TransportTypeController::class, 'update'])->name('transport-types.update')->middleware('bouncer:Transport\TransportType,update');
        Route::post('/delete', [TransportTypeController::class, 'destroy'])->name('transport-types.delete')->middleware('bouncer:Transport\TransportType,delete');
    });
});

Route::prefix('occupancy')->group(function () {
    Route::get('/create', [TransportOccupancyController::class, 'create'])->name('occupancy.create')->middleware('bouncer:Transport\TransportOccupancy,create');
    Route::post('/create', [TransportOccupancyController::class, 'store'])->name('occupancy.store')->middleware('bouncer:Transport\TransportOccupancy,create');
    Route::prefix('{occupancy}')->group(function () {
        Route::get('/update', [TransportOccupancyController::class, 'edit'])->name('occupancy.edit')->middleware('bouncer:Transport\TransportOccupancy,update');
        Route::post('/update', [TransportOccupancyController::class, 'update'])->name('occupancy.update')->middleware('bouncer:Transport\TransportOccupancy,update');
        Route::post('/delete', [TransportOccupancyController::class, 'destroy'])->name('occupancy.delete')->middleware('bouncer:Transport\TransportOccupancy,delete');
    });
});

