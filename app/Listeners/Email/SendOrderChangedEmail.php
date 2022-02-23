<?php

namespace App\Listeners\Email;

use App\Events\Order\OrderCreatedEvent;
use App\Events\Parent\OrderEvent;
use App\Repository\MailRepository;

class SendOrderChangedEmail
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
     * @param  OrderEvent  $event
     * @return void
     */
    public function handle(OrderEvent $event)
    {
        if (!$event->shouldInvoice) return;
        MailRepository::sendMailable('order-changed', $event->order->leadBooker->customer->email_address, $event->order);
    }
}
