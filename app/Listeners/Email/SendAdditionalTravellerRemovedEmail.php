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
        if (!$event->shouldInvoice) return;
        $customer = $event->orderCustomer->customer;
        if (isset($customer->email_address)) {
            MailRepository::sendMailable('additional-traveller-removed', $customer->email_address, $event->orderCustomer);
        }
    }
}
