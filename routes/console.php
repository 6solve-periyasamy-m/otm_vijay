<?php

use App\Models\Order\Order;
use App\Repository\Authentication\UserUpgrader;
use App\Repository\Model\Order\InvoiceUpgrader;
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


Artisan::command('update:all', function () {
    $this->info("Recaching all orders");
    $this->runCommand('order:recache', [], $this->output);
    echo PHP_EOL;
    $this->runCommand('update:invoices', [], $this->output);
    echo PHP_EOL;
    $this->runCommand('update:users', [], $this->output);
    echo PHP_EOL;

});

Artisan::command('update:invoices', function () {
    $this->info("Updating outdated invoices");
    InvoiceUpgrader::upgradeAll($this->output);
});

Artisan::command('update:users', function () {
    $this->info("Updating Default Users");
    (new UserUpgrader())->run_upgrades();
});