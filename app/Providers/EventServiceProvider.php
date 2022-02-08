<?php

namespace App\Providers;

use App\Events\Order\Adjustment\AdjustmentCreatedEvent;
use App\Events\Order\Adjustment\AdjustmentEditedEvent;
use App\Events\Order\Adjustment\AdjustmentRemovedEvent;
use App\Events\Order\Customer\Adjustment\CustomerAdjustmentCreatedEvent;
use App\Events\Order\Customer\Adjustment\CustomerAdjustmentEditedEvent;
use App\Events\Order\Customer\Adjustment\CustomerAdjustmentRemovedEvent;
use App\Events\Order\Customer\Component\OrderCustomerComponentAddedEvent;
use App\Events\Order\Customer\Component\OrderCustomerComponentEditedEvent;
use App\Events\Order\Customer\Component\OrderCustomerComponentRemovedEvent;
use App\Events\Order\Customer\OrderCustomerCreatedEvent;
use App\Events\Order\Customer\OrderCustomerEditedEvent;
use App\Events\Order\Customer\OrderCustomerRemovedEvent;
use App\Events\Order\OrderCancelledEvent;
use App\Events\Order\OrderCreatedEvent;
use App\Events\Order\OrderEditedEvent;
use App\Events\Order\OrderRestoredEvent;
use App\Events\Order\Payment\PaymentCreatedEvent;
use App\Events\Order\Payment\PaymentEditedEvent;
use App\Events\Order\Payment\PaymentRemovedEvent;
use App\Listeners\Email\SendAdditionalTravellerAddedEmail;
use App\Listeners\Email\SendAdditionalTravellerRemovedEmail;
use App\Listeners\Email\SendOrderCancelledEmail;
use App\Listeners\Email\SendOrderChangedEmail;
use App\Listeners\InvoiceUpdateListener;
use App\Listeners\CheckoutSuccessfulListener;
use App\Listeners\Email\SendBookingConfirmedEmail;
use App\Listeners\Email\SendPaymentMadeEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderCreatedEvent::class => [
            SendBookingConfirmedEmail::class,
            InvoiceUpdateListener::class,
        ],
        PaymentCreatedEvent::class => [
            SendPaymentMadeEmail::class,
            InvoiceUpdateListener::class,
        ],
        OrderCancelledEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderCancelledEmail::class,
        ],
        OrderRestoredEvent::class => [
            InvoiceUpdateListener::class,
        ],
        OrderEditedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        PaymentEditedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        PaymentRemovedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        AdjustmentCreatedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        AdjustmentEditedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        AdjustmentRemovedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        OrderCustomerCreatedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
            SendAdditionalTravellerAddedEmail::class,
        ],
        OrderCustomerEditedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        OrderCustomerRemovedEvent::class => [
            InvoiceUpdateListener::class,
            SendAdditionalTravellerRemovedEmail::class,
            SendOrderChangedEmail::class,
        ],
        OrderCustomerComponentAddedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        OrderCustomerComponentEditedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        OrderCustomerComponentRemovedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        CustomerAdjustmentCreatedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        CustomerAdjustmentEditedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        CustomerAdjustmentRemovedEvent::class => [
            InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        'stripe-webhooks::checkout.session.completed' => [
            CheckoutSuccessfulListener::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
