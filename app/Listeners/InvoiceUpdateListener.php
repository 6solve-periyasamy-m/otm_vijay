<?php

namespace App\Listeners;

use App\Events\Parent\OrderEvent;
use App\Repository\StaticOrderRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class InvoiceUpdateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param OrderEvent $event
     * @return void
     */
    public function handle(OrderEvent $event)
    {
        if ($event->shouldInvoice) {
            StaticOrderRepository::saveInvoice($event->order);
        }
    }
}
