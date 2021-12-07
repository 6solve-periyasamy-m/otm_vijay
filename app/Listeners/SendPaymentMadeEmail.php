<?php

namespace App\Listeners;

use App\Events\PaymentMadeEvent;
use App\Mail\PaymentMadeMailable;
use App\Mail\RefundGivenMailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPaymentMadeEmail
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
     * @param  PaymentMadeEvent  $event
     * @return void
     */
    public function handle(PaymentMadeEvent $event)
    {
        if ($event->payment->payment_type === 'Refund') {
            Mail::to($event->payment->order->leadBooker->customer->email_address)->send(new RefundGivenMailable($event->payment));
        } else {
            Mail::to($event->payment->order->leadBooker->customer->email_address)->send(new PaymentMadeMailable($event->payment));
        }
    }
}
