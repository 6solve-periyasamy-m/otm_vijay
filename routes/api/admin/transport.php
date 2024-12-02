<?php

use App\Actions\Transport\DeleteTransport;
use Illuminate\Support\Facades\Route;


Route::delete('/delete', DeleteTransport::class)->name('delete');
