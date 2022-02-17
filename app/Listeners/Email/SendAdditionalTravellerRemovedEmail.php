<?php

namespace App\Listeners\Email;

use App\Events\Order\Customer\OrderCustomerCreatedEvent;
use App\Events\Order\Customer\OrderCustomerRemovedEvent;
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
     * @param OrderCustomerRemovedEvent $event
     * @return void
     */
    public function handle(OrderCustomerRemovedEvent $event)
    {
        MailRepository::sendMailable('additional-traveller-removed', $event->orderCustomer->customer->email_address, $event->orderCustomer);
    }
}
