<?php

namespace App\Console\Commands;

use App\Models\Tour\Tour;
use App\Repository\TourRepository;
use Illuminate\Console\Command;

class AssignTemplatesToTours extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'template:assign-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign templating to all existing tours';

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
            $tour->repository->autoAssignTemplating();
        }
        return 0;
    }
}
