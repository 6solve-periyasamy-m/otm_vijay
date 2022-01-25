<?php

namespace App\Listeners;

use App\Events\Order\OrderCreatedEvent;
use App\Mail\BookingConfirmationMailable;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
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
            Mail::to($event->order->leadBooker->customer->email_address)->send(new BookingConfirmationMailable($event->order));
        } catch (Exception $e) {
            Log::error($e);
        }

    }
}
