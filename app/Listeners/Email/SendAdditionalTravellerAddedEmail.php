<?php

namespace App\Listeners\Email;

use App\Events\Order\Customer\OrderCustomerCreatedEvent;
use App\Repository\Mailing\MailRepository;

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
            MailRepository::sendMailable('additional-traveller-added', $customer->email_address, $event->orderCustomer);
        }
    }
}
