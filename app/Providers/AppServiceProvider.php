<?php

namespace App\Providers;

use App\Models\Customer\Customer;
use App\Models\User;
use App\Transport\MinimalLogTransport;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Laravel\Cashier\Cashier;
use Laravel\Pennant\Feature;
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
        Password::defaults(function () {
            return Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised();
        });
        Feature::define('bleeding-edge', function (User $user) {
            return config('app.features.bleeding-edge');
        });
        Feature::define('is-kpt', function (User $user) {
            return config('app.features.is-kpt');
        });
    }
}
