<?php

namespace App\Console\Commands;

use App\Imports\AirportImport;
use Excel;
use Illuminate\Console\Command;

class ImportAirport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:airport {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Airport from CSV';

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
        Excel::import(new AirportImport, $file);
        return 0;
    }
}
