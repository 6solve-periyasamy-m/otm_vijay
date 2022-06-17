<?php

namespace App\Console\Commands;

use App\Imports\CustomerImport;
use Excel;
use Illuminate\Console\Command;

class ImportCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:customer {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Customers from CSV';

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
        Excel::import(new CustomerImport, $file);
        return 0;
    }
}
