<?php

use App\Models\Order\Order;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('order:recache', function () {
    $orders = Order::all();
    $bar = $this->output->createProgressBar($orders->count());
    $bar->start();
    foreach ($orders as $order) {
        $order->repository->refresh();
        $bar->advance();
    }
    $bar->finish();
})->purpose('Refresh the cache on all orders');
