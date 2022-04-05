<?php

namespace App\Console\Commands;

use App\Models\Tour;
use App\Repository\LocationsRepository;
use App\Repository\TourRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RunUpgradeRetrospective extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upgrades:retrospect';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify the tour upgrades';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (Tour::all() as $tour) {
            TourRepository::fixUpgrades($tour);
        }
        return 0;
    }
}
