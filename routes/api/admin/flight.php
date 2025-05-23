<?php

use App\Actions\Flight\DeleteFlight;
use Illuminate\Support\Facades\Route;


Route::delete('/delete', DeleteFlight::class)->name('delete');
