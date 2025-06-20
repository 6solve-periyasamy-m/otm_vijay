<?php

use App\Http\Controllers\Admin\Order\Component\OrderComponentCostReportController;
use App\Http\Controllers\Admin\Reporting\AtolController;
use App\Http\Controllers\Admin\Reporting\ManifestController;
use App\Http\Controllers\Admin\Reporting\ReportController;

Route::get('/orders', [ReportController::class, 'getOrderReport'])->name('reports.order');
Route::get('/orders/{extension}', [ReportController::class, 'exportOrderReport'])->name('reports.order.export');
Route::get('/final-payment', [ReportController::class, 'getFinalPaymentReport'])->name('reports.final-payment');
Route::get('/final-payment/{extension}', [ReportController::class, 'exportFinalPaymentReport'])->name('reports.final-payment.export');
Route::get('/tour-stock', [ReportController::class, 'getTourStockReport'])->name('reports.tour-stock');
Route::get('/tour-stock/{extension}', [ReportController::class, 'exportTourStockReport'])->name('reports.tour-stock.export');
Route::get('/payments', [ReportController::class, 'getPaymentsReport'])->name('reports.payment');
Route::get('/payments/{extension}', [ReportController::class, 'exportPaymentsReport'])->name('reports.payment.export');
Route::get('/flight-manifest', [ReportController::class, 'getFlightManifestReport'])->name('reports.flight-manifest');
Route::get('/flight-manifest/{extension}', [ReportController::class, 'exportFlightManifestReport'])->name('reports.flight-manifest.export');
Route::get('/activities', [ReportController::class, 'getActivitiesReport'])->name('reports.activities');
Route::get('/activities/{extension}', [ReportController::class, 'exportActivitiesReport'])->name('reports.activities.export');
Route::get('/abandoned-bookings', [ReportController::class, 'getAbandonedBookingsReport'])->name('reports.abandoned-bookings');
Route::get('/abandoned-bookings/{extension}', [ReportController::class, 'exportAbandonedBookingsReport'])->name('reports.abandoned-bookings.export');
Route::get('/abandoned-bookings-hidden', [ReportController::class, 'getAbandonedBookingsHiddenReport'])->name('reports.abandoned-bookings-hidden');
Route::get('/abandoned-bookings-hidden/{extension}', [ReportController::class, 'exportAbandonedBookingsHiddenReport'])->name('reports.abandoned-bookings-hidden.export');
Route::get('/reminders/export/{extension}/{max?}/{min?}', [ReportController::class, 'exportOrderRemindersReport'])->name('reports.reminders.export');
Route::get('/reminders/{max?}/{min?}', [ReportController::class, 'getOrderRemindersReport'])->name('reports.reminders');
Route::get('/merchandise', [ReportController::class, 'getOrderMerchandiseReport'])->name('reports.merchandise');
Route::get('/merchandise/{extension}', [ReportController::class, 'exportOrderMerchandiseReport'])->name('reports.merchandise.export');
Route::get('/rooming', [ReportController::class, 'getRoomingReport'])->name('reports.rooming');
Route::get('/rooming/{extension}', [ReportController::class, 'exportRoomingReport'])->name('reports.rooming.export');
Route::get('/installment-revenue', [ReportController::class, 'getInstallmentRevenueReport'])->name('reports.installment-revenue');
Route::get('/installment-revenue/{extension}', [ReportController::class, 'exportInstallmentRevenueReport'])->name('reports.installment-revenue.export');
Route::get('/order/activity/cost', [OrderComponentCostReportController::class, 'getOrderActivityReport'])->name('reports.component.cost.activity');
Route::get('/order/activity/cost/{extension}', [OrderComponentCostReportController::class, 'exportOrderActivityReport'])->name('reports.component.cost.activity.export');
Route::prefix('manifest')->name('reports.manifest.')->group(function () {
    Route::prefix('activity')->name('activity.')->group(function () {
        Route::get('/', [ManifestController::class, 'viewActivity'])->name('view');
        Route::get('/export/{extension}', [ManifestController::class, 'exportActivity'])->name('export');
    });
    Route::prefix('flight')->name('flight.')->group(function () {
        Route::get('/', [ManifestController::class, 'viewFlight'])->name('view');
        Route::get('/export/{extension}', [ManifestController::class, 'exportFlight'])->name('export');
    });
    Route::prefix('transport')->name('transport.')->group(function () {
        Route::get('/', [ManifestController::class, 'viewTransport'])->name('view');
        Route::get('/export/{extension}', [ManifestController::class, 'exportTransport'])->name('export');
    });
    Route::prefix('merchandise')->name('merchandise.')->group(function () {
        Route::get('/', [ManifestController::class, 'viewMerchandise'])->name('view');
        Route::get('/export/{extension}', [ManifestController::class, 'exportMerchandise'])->name('export');
    });
});
Route::prefix('atol')->name('reports.atol.')->group(function () {
    Route::get('/ordered/{year}/{quarter}', [AtolController::class, 'getOrderedInQuarterReport'])->name('ordered');
    Route::get('/departed-in/{year}/{quarter}', [AtolController::class, 'getDepartingInQuarterReport'])->name('departed-in');
    Route::get('/departs-after/{year}/{quarter}', [AtolController::class, 'getDepartingAfterQuarterReport'])->name('departs-after');
    Route::prefix('export')->name('export.')->group(function () {
        Route::get('/ordered/{year}/{quarter}/{extension?}', [AtolController::class, 'exportOrderedInQuarterReport'])->name('ordered');
        Route::get('/departed-in/{year}/{quarter}/{extension?}', [AtolController::class, 'exportDepartingInQuarterReport'])->name('departed-in');
        Route::get('/departs-after/{year}/{quarter}/{extension?}', [AtolController::class, 'exportDepartingAfterQuarterReport'])->name('departs-after');
    });
    Route::prefix('certificates')->name('certificate.')->group(function () {
        Route::get('/ordered/{year}/{quarter}', [AtolController::class, 'exportOrderedInQuarterCertificates'])->name('ordered');
        Route::get('/departed-in/{year}/{quarter}', [AtolController::class, 'exportDepartsInQuarterCertificates'])->name('departed-in');
        Route::get('/departs-after/{year}/{quarter}', [AtolController::class, 'exportDepartsAfterQuarterCertificates'])->name('departs-after');
    });

});
