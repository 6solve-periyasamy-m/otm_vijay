<?php

namespace App\Listeners\Email;

use App\Events\Order\OrderCancelledEvent;
use App\Events\Parent\OrderEvent;

class SendOrderCancelledEmail
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
     * @param  OrderEvent $event
     * @return void
     */
    public function handle(OrderCancelledEvent $event)
    {
        if (!$event->shouldInvoice) return;
        $customer =  $event->order->leadBooker->customer;
        if (isset($customer->email_address)) {
            $event->sendMail('order-cancelled', $customer->email_address);

        }
    }
}
