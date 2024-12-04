<?php

use App\Actions\Activity\DeleteActivity;
use Illuminate\Support\Facades\Route;


Route::delete('/delete', DeleteActivity::class)->name('delete');
