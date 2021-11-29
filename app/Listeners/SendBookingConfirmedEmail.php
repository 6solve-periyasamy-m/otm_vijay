<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Mail\BookingConfirmation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

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
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        Mail::to($event->order->leadBooker->customer->email_address)->send(new BookingConfirmation($event->order));
    }
}
