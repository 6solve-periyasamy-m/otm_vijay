<?php

use App\Http\Controllers\Api\OrderController;

Route::prefix('reminder')->name('reminder.')->group(function () {
    Route::post('bulk', [OrderController::class, 'bulkSendOrderReminders'])->name('bulk');
});
