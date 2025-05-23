<?php

use App\Actions\Merchandise\DeleteMerchandise;
use Illuminate\Support\Facades\Route;


Route::delete('/delete', DeleteMerchandise::class)->name('delete');
