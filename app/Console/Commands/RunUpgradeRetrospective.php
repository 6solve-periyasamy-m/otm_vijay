<?php

namespace App\Console\Commands;

use App\Models\Tour\Tour;
use App\Repository\TourRepository;
use Illuminate\Console\Command;

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
            $tour->repository->fixUpgrades();
        }
        return 0;
    }
}
