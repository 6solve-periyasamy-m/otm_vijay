<?php

namespace App\Listeners\Email;

use App\Events\Order\Customer\OrderCustomerCreatedEvent;
use App\Repository\MailRepository;

class SendAdditionalTravellerRemovedEmail
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
        MailRepository::sendMailable('additional-traveller-removed', $event->orderCustomer->customer->email_address, $event->orderCustomer);
    }
}
