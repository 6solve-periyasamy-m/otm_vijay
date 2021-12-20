<?php

namespace App\Providers;

use App\Repository\Facades\StringFormatter;
use Illuminate\Support\ServiceProvider;

class StringFormatProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind('string-formatter', function () {
            return new StringFormatter;
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
