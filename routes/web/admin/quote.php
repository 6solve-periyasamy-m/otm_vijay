<?php

use App\Http\Controllers\Admin\Quote\QuoteComponentController;
use App\Http\Controllers\Admin\Quote\QuoteController;
use App\Http\Controllers\Admin\Quote\QuoteInstallmentController;
use App\Http\Controllers\Admin\Quote\QuotePricePointController;
use App\Http\Controllers\Admin\Quote\QuoteSectionController;
use App\Http\Controllers\Admin\Quote\QuoteStatusController;

Route::get('/', [QuoteController::class, 'index'])->name('all')->middleware('bouncer:Quote\Quote,read');
Route::post('/create', [QuoteController::class, 'storeBespoke'])->name('store-bespoke')->middleware('bouncer:Quote\Quote,create');
Route::get('/create/{tour?}', [QuoteController::class, 'create'])->name('create')->middleware('bouncer:Quote\Quote,create');
Route::post('/create/{tour}', [QuoteController::class, 'storeBasic'])->name('store-basic')->middleware('bouncer:Quote\Quote,create');
Route::prefix('{quote}')->group(function () {
    Route::get('/', [QuoteController::class, 'view'])->name('view')->middleware('bouncer:Quote\Quote,read');
    Route::get('/accommodation', [QuoteController::class, 'accommodation'])->name('accommodation')->middleware('bouncer:Quote\Quote,update');
    Route::get('/update', [QuoteController::class, 'edit'])->name('edit')->middleware('bouncer:Quote\Quote,update');
    Route::post('/update', [QuoteController::class, 'update'])->name('update')->middleware('bouncer:Quote\Quote,update');
    Route::get('/unlink', [QuoteComponentController::class, 'unlink'])->name('unlink')->middleware('bouncer:Quote\Quote,update');
    Route::get('/preview', [QuoteController::class, 'preview'])->name('preview')->middleware('bouncer:Quote\Quote,read');
    Route::get('/costing', [QuoteController::class, 'costing'])->name('costing')->middleware('bouncer:Quote\Quote,costing');
    Route::get('/conversion', [QuoteController::class, 'conversion'])->name('conversion')->middleware('bouncer:Quote\Quote,update');
    Route::post('/convert', [QuoteController::class, 'convert'])->name('convert')->middleware('bouncer:Quote\Quote,update');
    Route::post('/send', [QuoteController::class, 'send'])->name('send')->middleware('bouncer:Quote\Quote,update');
    Route::post('/delete', [QuoteController::class, 'delete'])->name('delete')->middleware('bouncer:Quote\Quote,delete');
    Route::get('/delete/force', [QuoteController::class, 'forceDelete'])->name('delete.force')->middleware('bouncer:Quote\Quote,delete');
    Route::prefix('section')->name('section.')->group(function () {
        Route::get('/create', [QuoteSectionController::class, 'create'])->name('create')->middleware('bouncer:Quote\Quote,update');
        Route::post('/create', [QuoteSectionController::class, 'store'])->name('store')->middleware('bouncer:Quote\Quote,update');
        Route::get('/hide-all', [QuoteSectionController::class, 'hideAll'])->name('hide')->middleware('bouncer:Quote\Quote,update');
        Route::get('/show-all', [QuoteSectionController::class, 'showAll'])->name('show')->middleware('bouncer:Quote\Quote,update');
        Route::prefix('/{section}')->group(function () {
            Route::get('/update', [QuoteSectionController::class, 'edit'])->name('edit')->middleware('bouncer:Quote\Quote,update');
            Route::post('/update', [QuoteSectionController::class, 'update'])->name('update')->middleware('bouncer:Quote\Quote,update');
            Route::post('/delete', [QuoteSectionController::class, 'delete'])->name('delete')->middleware('bouncer:Quote\Quote,update');
        });
    });
    Route::prefix('sent/{sent}')->name('sent.')->group(function () {
        Route::get('/resend', [QuoteController::class, 'resend'])->name('resend')->middleware('bouncer:Quote\Quote,update');
        Route::get('/rebuild', [QuoteController::class, 'rebuild'])->name('rebuild')->middleware('bouncer:Quote\Quote,update');
        Route::get('/document', [QuoteController::class, 'document'])->name('view')->middleware('bouncer:Quote\Quote,read');
    });
    Route::prefix('status')->name('status.')->group(function () {
        Route::get('/changes', [QuoteStatusController::class, 'changes'])->name('changes')->middleware('bouncer:Quote\Quote,update');
        Route::get('/approve', [QuoteStatusController::class, 'approve'])->name('approve')->middleware('bouncer:Quote\Quote,update');
        Route::get('/close', [QuoteStatusController::class, 'close'])->name('close')->middleware('bouncer:Quote\Quote,read');
    });
    Route::prefix('installment')->name('installments.')->group(function () {
        Route::post('/create', [QuoteInstallmentController::class, 'store'])->name('store')->middleware('bouncer:Quote\Quote,update');
        Route::post('/{installment}/update', [QuoteInstallmentController::class, 'update'])->name('update')->middleware('bouncer:Quote\Quote,update');
        Route::post('/{installment}/delete', [QuoteInstallmentController::class, 'delete'])->name('delete')->middleware('bouncer:Quote\Quote,update');
    });
    Route::prefix('price-point')->name('price-points.')->group(function () {
        Route::post('/create', [QuotePricePointController::class, 'store'])->name('store')->middleware('bouncer:Quote\Quote,update');
        Route::post('/{pricePoint}/update', [QuotePricePointController::class, 'update'])->name('update')->middleware('bouncer:Quote\Quote,update');
        Route::post('/{pricePoint}/delete', [QuotePricePointController::class, 'delete'])->name('delete')->middleware('bouncer:Quote\Quote,update');
    });
    Route::prefix('component')->name('components.')->group(function () {
        Route::get('/add', [QuoteComponentController::class, 'add'])->name('add')->middleware('bouncer:Quote\Quote,update');
        Route::get('/{type}/{id}/convert', [QuoteComponentController::class, 'convert'])->name('convert')->middleware('bouncer:Quote\Quote,update');
        Route::prefix('{type}/{id}')->group(function () {
            Route::get('/update', [QuoteComponentController::class, 'edit'])->name('edit')->middleware('bouncer:Quote\Quote,update');
            Route::post('/update', [QuoteComponentController::class, 'update'])->name('update')->middleware('bouncer:Quote\Quote,update');
            Route::post('/delete', [QuoteComponentController::class, 'delete'])->name('delete')->middleware('bouncer:Quote\Quote,update');
        });
    });
});
