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
use App\Listeners\CheckoutSuccessfulListener;
use App\Listeners\Email\SendAdditionalTravellerAddedEmail;
use App\Listeners\Email\SendAdditionalTravellerRemovedEmail;
use App\Listeners\Email\SendBookingConfirmedEmail;
use App\Listeners\Email\SendOrderCancelledEmail;
use App\Listeners\Email\SendOrderChangedEmail;
use App\Listeners\Email\SendPaymentMadeEmail;
use App\Models\Order\Adjustment\ManualAdjustment;
use App\Models\Order\Adjustment\OrderCustomerAdjustment;
use App\Models\Order\Component\OrderAccommodation;
use App\Models\Order\Component\OrderActivity;
use App\Models\Order\Component\OrderFlight;
use App\Models\Order\Component\OrderMerchandise;
use App\Models\Order\Component\OrderTransport;
use App\Models\Order\Order;
use App\Models\Order\OrderCustomer;
use App\Models\Order\Payment\Payment;
use App\Observers\Order\Adjustment\ManualAdjustmentObserver;
use App\Observers\Order\Adjustment\OrderCustomerAdjustmentObserver;
use App\Observers\Order\Component\OrderAccommodationObserver;
use App\Observers\Order\Component\OrderActivityObserver;
use App\Observers\Order\Component\OrderFlightObserver;
use App\Observers\Order\Component\OrderMerchandiseObserver;
use App\Observers\Order\Component\OrderTransportObserver;
use App\Observers\Order\OrderCustomerObserver;
use App\Observers\Order\OrderObserver;
use App\Observers\Order\Payment\PaymentObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

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
            //InvoiceUpdateListener::class,
        ],
        PaymentCreatedEvent::class => [
            SendPaymentMadeEmail::class,
            //InvoiceUpdateListener::class,
        ],
        OrderCancelledEvent::class => [
            //InvoiceUpdateListener::class,
            SendOrderCancelledEmail::class,
        ],
        OrderRestoredEvent::class => [
            //InvoiceUpdateListener::class,
        ],
        OrderEditedEvent::class => [
            //InvoiceUpdateListener::class,
            //SendOrderChangedEmail::class,
        ],
        PaymentEditedEvent::class => [
            //InvoiceUpdateListener::class,
            //SendOrderChangedEmail::class,
        ],
        PaymentRemovedEvent::class => [
            //InvoiceUpdateListener::class,
            //SendOrderChangedEmail::class,
        ],
        AdjustmentCreatedEvent::class => [
            //InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        AdjustmentEditedEvent::class => [
            //InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        AdjustmentRemovedEvent::class => [
            //InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        OrderCustomerCreatedEvent::class => [
            //InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
            SendAdditionalTravellerAddedEmail::class,
        ],
        OrderCustomerEditedEvent::class => [
            //InvoiceUpdateListener::class,
            //SendOrderChangedEmail::class,
        ],
        OrderCustomerRemovedEvent::class => [
            //InvoiceUpdateListener::class,
            SendAdditionalTravellerRemovedEmail::class,
            SendOrderChangedEmail::class,
        ],
        OrderCustomerComponentAddedEvent::class => [
            //InvoiceUpdateListener::class,
            //SendOrderChangedEmail::class,
        ],
        OrderCustomerComponentEditedEvent::class => [
            //InvoiceUpdateListener::class,
            //SendOrderChangedEmail::class,
        ],
        OrderCustomerComponentRemovedEvent::class => [
            //InvoiceUpdateListener::class,
            //SendOrderChangedEmail::class,
        ],
        CustomerAdjustmentCreatedEvent::class => [
            //InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        CustomerAdjustmentEditedEvent::class => [
            //InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        CustomerAdjustmentRemovedEvent::class => [
            //InvoiceUpdateListener::class,
            SendOrderChangedEmail::class,
        ],
        'stripe-webhooks::charge.succeeded' => [
            CheckoutSuccessfulListener::class,
        ]
    ];

    protected $observers = [
        Order::class => [OrderObserver::class,],
        OrderCustomer::class => [OrderCustomerObserver::class,],
        ManualAdjustment::class => [ManualAdjustmentObserver::class,],
        OrderCustomerAdjustment::class => [OrderCustomerAdjustmentObserver::class,],
        Payment::class => [PaymentObserver::class,],
        OrderAccommodation::class => [OrderAccommodationObserver::class,],
        OrderActivity::class => [OrderActivityObserver::class,],
        OrderFlight::class => [OrderFlightObserver::class,],
        OrderMerchandise::class => [OrderMerchandiseObserver::class,],
        OrderTransport::class => [OrderTransportObserver::class,],
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
