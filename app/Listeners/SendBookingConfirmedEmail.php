<?php

namespace App\Listeners;

use App\Events\Order\OrderCreatedEvent;
use App\Repository\MailRepository;
use Exception;
use Log;

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
        try {
            MailRepository::sendMailable('booking-confirmed', $event->order->leadBooker->email, $event->order);
        } catch (Exception $e) {
            Log::error($e);
        }

    }
}
