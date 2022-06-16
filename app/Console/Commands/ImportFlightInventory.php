<?php

namespace App\Console\Commands;

use App\Imports\FlightInventoryImport;
use Excel;
use Illuminate\Console\Command;

class ImportFlightInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:flight-inventory {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Flight Inventory from CSV';

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
        $file = $this->argument('file');
        Excel::import(new FlightInventoryImport, $file);
        return 0;
    }
}
