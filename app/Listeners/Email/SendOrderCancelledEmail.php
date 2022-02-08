<?php

namespace App\Listeners\Email;

use App\Events\Order\OrderCreatedEvent;
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
     * @param  OrderCreatedEvent  $event
     * @return void
     */
    public function handle(OrderCreatedEvent $event)
    {
        MailRepository::sendMailable('order-cancelled', $event->order->leadBooker->email, $event->order);
    }
}
