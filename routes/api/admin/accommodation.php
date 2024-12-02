<?php

use App\Actions\Accommodation\DeleteAccommodation;
use Illuminate\Support\Facades\Route;


Route::delete('/delete', DeleteAccommodation::class)->name('delete');
