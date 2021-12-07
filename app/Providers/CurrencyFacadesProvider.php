<?php

namespace App\Providers;

use App\Repository\Facades\Currency;
use Illuminate\Support\ServiceProvider;

class CurrencyFacadesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind('currency', function () {
            return new Currency;
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
