<?php

namespace App\Console\Commands;

use App\Imports\AccommodationInventoryImport;
use Illuminate\Console\Command;
use Excel;

class ImportAccommodationInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:accommodation-inventory {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Accommodation Inventory from CSV';

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
        Excel::import(new AccommodationInventoryImport, $file);
        return 0;
    }
}
