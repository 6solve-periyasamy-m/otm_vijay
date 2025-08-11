<?php

use App\Models\Booking\Booking;
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
    $errors = "";
    $bar->start();
    foreach ($orders as $order) {
        try {
            $order->repository->refresh();
        } catch (Exception $e) {
            try {
                \Log::error($e);
            } catch (Exception $e) {
                $errors .= "Failed to log error: " . $e->getMessage() . PHP_EOL;
            }
            $errors .=  "Failed to refresh order: " . $order->booking_reference . PHP_EOL;
        }
        $bar->advance();
    }
    $bar->finish();
    if (!empty($errors)) {
        echo PHP_EOL . $errors;
    }
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

Artisan::command('booking:prune-null', function () {
    $query = Booking::nullLead();
    $count = $query->count();
    $confirm = $this->ask("This will remove {$count} bookings, are you sure? (y/n)");
    if ($confirm === 'y') {
        $this->info('Starting now, this may take a while...');
        $bar = $this->output->createProgressBar($count);
        $bar->start();
        foreach ($query->get() as $booking) {
            $booking->repository->delete();
            $bar->advance();
        }
        $bar->finish();
    }
    $this->info("{$count} bookings have been pruned");
});