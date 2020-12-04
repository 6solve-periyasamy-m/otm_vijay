<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\OrderCustomerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('customers', [HomeController::class, 'showLogin'])->middleware('auth:customers')->name('login');

//Route::get('login', [HomeController::class, 'showLogin'])->name('login');
Route::get('login', [HomeController::class, 'doLogin']);

Route::get('logout', [LoginController::class, 'logout'])->name('logout');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::get('/orders-users-components/{id}', [OrderCustomerController::class, 'customerComponents'])->name('customerComponents');
});


