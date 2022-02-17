<?php

namespace App\Listeners\Email;

use App\Events\Order\OrderCancelledEvent;
use App\Events\Order\OrderCreatedEvent;
use App\Events\Parent\OrderEvent;
use App\Repository\MailRepository;

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
     * @param  OrderEvent  $event
     * @return void
     */
    public function handle(OrderCancelledEvent $event)
    {
        MailRepository::sendMailable('order-cancelled', $event->order->leadBooker->customer->email_address, $event->order);
    }
}
