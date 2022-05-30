<?php

namespace App\Console\Commands;

use App\Models\Customer;
use DB;
use Exception;
use Faker\Factory as Faker;
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
        $faker = Faker::create();
        foreach (Customer::withTrashed()->get() as $customer) {
            do {
                $succeeded = false;
                try {
                    $customer->update([
                        'first_name' => $faker->firstName,
                        'last_name' => $faker->lastName,
                        'mobile_number' => $faker->phoneNumber,
                        'email_address' => isset($customer->email_address) ? $faker->email : null,
                        'passport_number' => isset($customer->passport_number) ? 123456 : null,
                    ]);
                    $customer->save();
                    $this->info("Anonymized {$customer->full_name}");
                    $succeeded = true;
                } catch (Exception) {continue;}
            } while (!$succeeded);
        }
        return 0;
    }
}
