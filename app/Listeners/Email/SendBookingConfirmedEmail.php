<?php

namespace App\Listeners\Email;

use App\Events\Order\OrderCreatedEvent;
use App\Repository\MailRepository;

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
        MailRepository::sendMailable('booking-confirmed', $event->order->leadBooker->customer->email_address, $event->order);
    }
}
