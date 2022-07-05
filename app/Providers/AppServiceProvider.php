<?php

namespace App\Providers;

use App\Models\Customer\Customer;
use App\Transport\MinimalLogTransport;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Mail;
use Stripe\Stripe;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Cashier::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Cashier::useCustomerModel(Customer::class);
        Stripe::setApiKey(config('app.gateways.stripe.secret'));
        Mail::extend('minimal-log', function (array $config = []) {
            return new MinimalLogTransport();
        });
    }
}
