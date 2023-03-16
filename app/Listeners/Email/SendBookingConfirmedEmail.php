<?php

namespace App\Listeners\Email;

use App\Events\Order\OrderCreatedEvent;

class SendBookingConfirmedEmail
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
     * @param  OrderCreatedEvent  $event
     * @return void
     */
    public function handle(OrderCreatedEvent $event)
    {
        if (!$event->shouldInvoice) return;
        $customer = $event->order->leadBooker->customer;
        if (isset($customer->email_address)) {
            $event->sendMail('booking-confirmation', $customer->email_address);
        }
    }
}
