<?php

namespace App\Listeners\Email;

use App\Events\Order\Customer\OrderCustomerCreatedEvent;
use App\Repository\MailRepository;

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
        MailRepository::sendMailable('additional-traveller-added', $event->orderCustomer->customer->email_address, $event->orderCustomer);
    }
}
