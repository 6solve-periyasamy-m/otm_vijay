<?php

use App\Http\Controllers\Admin\Reporting\BespokeReportController;

Route::get('/', function () {
    return redirect()->route('reports.all');
})->name('reports.bespoke.all');
Route::get('create/{parent}', [BespokeReportController::class, 'create'])->name('reports.bespoke.create');
Route::post('temporary', [BespokeReportController::class, 'showTemporary'])->name('reports.bespoke.temporary.show');
Route::prefix('{report}')->group(function () {
    Route::get('view', [BespokeReportController::class, 'show'])->name('reports.bespoke.show');
    Route::get('edit', [BespokeReportController::class, 'edit'])->name('reports.bespoke.edit');
    Route::post('edit', [BespokeReportController::class, 'update'])->name('reports.bespoke.update');
    Route::get('export/{extension}', [BespokeReportController::class, 'export'])->name('reports.bespoke.export');
    Route::post('delete', [BespokeReportController::class, 'delete'])->name('reports.bespoke.delete');
});
