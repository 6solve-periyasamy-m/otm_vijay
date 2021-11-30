<?php

namespace App\Providers;

use App\Events\OrderCreatedEvent;
use App\Events\PaymentMadeEvent;
use App\Listeners\SendBookingConfirmedEmail;
use App\Listeners\SendPaymentMadeEmail;
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
        ],
        PaymentMadeEvent::class => [
          SendPaymentMadeEmail::class,
        ],
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
