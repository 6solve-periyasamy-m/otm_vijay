<?php

namespace App\Providers;

use App\Repository\Facades\Gateway;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class GatewayProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind('gateway', function () {
            return new Gateway();
        });
    }
}
