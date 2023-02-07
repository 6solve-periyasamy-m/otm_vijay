<?php

namespace App\Providers;

use App\Repository\Facades\Icon;
use Illuminate\Support\ServiceProvider;

class IconProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        app()->bind('icon', function () {
            return new Icon();
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
