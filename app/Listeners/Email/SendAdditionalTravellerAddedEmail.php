<?php

namespace App\Listeners\Email;

use App\Events\Order\Customer\OrderCustomerCreatedEvent;

class SendAdditionalTravellerAddedEmail
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
     * @param OrderCustomerCreatedEvent $event
     * @return void
     */
    public function handle(OrderCustomerCreatedEvent $event)
    {
        if (!$event->shouldInvoice) return;
        $customer = $event->orderCustomer->customer;
        if (isset($customer->email_address)) {
            $event->sendMail('additional-traveller-added', $customer->email_address);
        }
    }
}
