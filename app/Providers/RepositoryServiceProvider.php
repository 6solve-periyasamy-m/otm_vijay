<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\FlightsRepository;
use App\Models\Flight;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind("App\Repository\FlightRepositoryInterface", function() {
            return new FlightsRepository(new Flight());
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
