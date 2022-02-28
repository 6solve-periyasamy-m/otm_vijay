<?php

namespace App\Listeners\Email;

use App\Events\Order\Payment\PaymentCreatedEvent;
use App\Repository\MailRepository;

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
        if (!$event->shouldInvoice) return;
        $customer =  $event->order->leadBooker->customer;
        if ($event->payment->payment_type === 'Refund') {
            if (isset($customer->email_address)) {
                MailRepository::sendMailable('refund-given', $customer->email_address, $event->payment);
            }
        } else {
            if (isset($customer->email_address)) {
                MailRepository::sendMailable('payment-made', $customer->email_address, $event->payment);
            }
        }
    }
}
