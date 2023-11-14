<?php


use App\Http\Controllers\Admin\SupplierController;

Route::get('/', [SupplierController::class, 'index'])->name('index');
Route::get('/{supplier}', [SupplierController::class, 'view'])->name('view');
Route::get('/{supplier}/{contract}', [SupplierController::class, 'contract'])->name('contract');
