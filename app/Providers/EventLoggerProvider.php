<?php

namespace App\Providers;

use App\Repository\Facades\EventLogger;
use Illuminate\Support\ServiceProvider;

class EventLoggerProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind('event-logger', function () {
            return new EventLogger;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
