<?php

use App\Http\Controllers\Api\Admin\SurchargeController;
use Illuminate\Support\Facades\Route;

Route::post('/stripe/surcharge/set', [SurchargeController::class, 'setStripeSurcharge'])->name('surcharge.stripe.set');