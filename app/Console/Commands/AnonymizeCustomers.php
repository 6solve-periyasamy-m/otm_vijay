<?php

namespace App\Console\Commands;

use App\Models\Customer;
use DB;
use Exception;
use Illuminate\Console\Command;
use Log;
use Schema;

class AnonymizeCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:anonymize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Anonymize all customers (NON-REVERSIBLE)';

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
        Schema::disableForeignKeyConstraints();
        $customers = Customer::withTrashed()->orderBy('id', 'desc')->first()->id;
        $this->info("Found highest id of {$customers}");
        DB::statement('TRUNCATE customers;');
        while ($customers > 0) {
            try {
                $customer = Customer::factory()->create();
                if (!isset($customer)) continue;
                $customers--;
            } catch (Exception $e) {
                Log::error($e);
                continue;
            }
        }
        $customers = Customer::withTrashed()->count();
        $this->info("Generated {$customers} customers");
        Schema::enableForeignKeyConstraints();
        return 0;
    }
}
