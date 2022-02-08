<?php

namespace App\Listeners;

use App\Events\Order\Payment\PaymentCreatedEvent;
use App\Repository\MailRepository;
use Exception;
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
        if ($event->payment->payment_type === 'Refund') {
            MailRepository::sendMailable('refund-given', $event->order->leadBooker->email, $event->payment);
        } else {
            MailRepository::sendMailable('payment-made', $event->order->leadBooker->email, $event->payment);
        }
    }
}
