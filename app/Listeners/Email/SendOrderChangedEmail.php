<?php

namespace App\Listeners\Email;

use App\Events\Parent\OrderEvent;
use App\Repository\Mailing\MailRepository;

class SendOrderChangedEmail
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
    public function handle(OrderEvent $event)
    {
        if (!$event->shouldInvoice) return;
        $customer =   $event->order->leadBooker->customer;
        if (isset($customer->email_address)) {
            MailRepository::sendMailable('order-changed', $customer->email_address, $event->order);
        }
    }
}
