<?php

namespace App\Console\Commands;

use App\Imports\AccommodationImport;
use Illuminate\Console\Command;
use Excel;

class ImportAccommodation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:accommodation {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Accommodation from CSV';

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
        Excel::import(new AccommodationImport, $file);
        return 0;
    }
}
