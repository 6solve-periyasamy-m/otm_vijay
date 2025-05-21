<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order\Order;
use Illuminate\Support\Str;

class FixUnknownTravellers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:fix-unknown-travellers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rename Unknown Paying Travellers to TBC format for existing orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::whereHas('customers', function ($query) {
            $query->where('first_name', 'like', 'Unknown Paying Traveller%');
        })->get();

        foreach ($orders as $order) {
            $this->info("Fixing Order ID: {$order->id}");
            $tbcCounter = 1;
            $bookingRef = $order->booking_reference;
            $leadId = $order->lead_booker_id;
            $order->customers()
                ->where('customers.id', '!=', $leadId)
                ->where('first_name', 'like', 'Unknown Paying Traveller%')
                ->each(function ($customer) use (&$tbcCounter, $bookingRef) {
                    $customer->first_name = "TBC {$tbcCounter}";
                    $customer->last_name = "Paying - {$bookingRef}";
                    $customer->saveQuietly();
                    $tbcCounter++;
                });
        }
        $this->info('All unknown travellers have been updated.');
    }
}
