<?php

namespace App\Providers;

use App\Models\Flight\Flight;
use App\Repository\FlightsRepository;
use Illuminate\Support\ServiceProvider;

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
