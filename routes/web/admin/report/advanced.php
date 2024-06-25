<?php

use App\Http\Controllers\Admin\Reporting\AdvancedBespokeController;

Route::get('/create/{type}', [AdvancedBespokeController::class, 'create'])->name('reports.advanced.create');
Route::post('/store', [AdvancedBespokeController::class, 'store'])->name('reports.advanced.store');
Route::prefix('{report}')->group(function () {
    Route::get('/', [AdvancedBespokeController::class, 'view'])->name('reports.advanced.view');
    Route::get('/update', [AdvancedBespokeController::class, 'edit'])->name('reports.advanced.edit');
    Route::post('/update', [AdvancedBespokeController::class, 'update'])->name('reports.advanced.update');
    Route::post('/delete', [AdvancedBespokeController::class, 'delete'])->name('reports.advanced.delete');
});
