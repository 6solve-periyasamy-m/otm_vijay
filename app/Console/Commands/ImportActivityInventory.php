<?php

namespace App\Console\Commands;

use App\Imports\ActivityInventoryImport;
use Excel;
use Illuminate\Console\Command;

class ImportActivityInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:activity-inventory {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Activity Inventory from CSV';

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
        Excel::import(new ActivityInventoryImport, $file);
        return 0;
    }
}
