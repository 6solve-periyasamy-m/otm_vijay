<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MakeVoyagerSeeders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mvseeders:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'mvseeders:build creates the seeders required for each Voyager Table.  These seeders are then remmoved and replace existing ones in git.';

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
        return 0;
    }
}
