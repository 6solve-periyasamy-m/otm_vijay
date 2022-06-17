<?php

namespace App\Console\Commands;

use App\Imports\ActivityImport;
use Excel;
use Illuminate\Console\Command;

class ImportActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:activity {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Activity from CSV';

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
        Excel::import(new ActivityImport, $file);
        return 0;
    }
}
