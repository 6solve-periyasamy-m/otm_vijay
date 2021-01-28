<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use TCG\Voyager\Facades\Voyager;
use App\Actions\getTourComponentListAction;
use App\Actions\getOrderCustomersAction;
use App\Actions\getOrderCustomerComponentsAction;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Voyager::addAction(getTourComponentListAction::class);
        Voyager::addAction(getOrderCustomersAction::class);
        Voyager::addAction(getOrderCustomerComponentsAction::class);

    }
}
