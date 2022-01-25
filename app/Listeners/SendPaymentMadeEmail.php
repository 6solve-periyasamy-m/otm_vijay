<?php

namespace App\Listeners;

use App\Events\Order\Payment\PaymentCreatedEvent;
use App\Mail\PaymentMadeMailable;
use App\Mail\RefundGivenMailable;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use Log;

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
     * @param  PaymentCreatedEvent  $event
     * @return void
     */
    public function handle(PaymentCreatedEvent $event)
    {
        try {
            if ($event->payment->payment_type === 'Refund') {
                Mail::to($event->payment->order->leadBooker->customer->email_address)->send(new RefundGivenMailable($event->payment));
            } else {
                Mail::to($event->payment->order->leadBooker->customer->email_address)->send(new PaymentMadeMailable($event->payment));
            }
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
