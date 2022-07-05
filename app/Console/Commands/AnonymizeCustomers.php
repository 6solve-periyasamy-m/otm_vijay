<?php

namespace App\Console\Commands;

use App\Models\Customer\Customer;
use Exception;
use Faker\Factory as Faker;
use Illuminate\Console\Command;

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
        if (!config('app.anonymization', false)) {
            $this->error('Anonymization is not allowed on this instance. To enable, add: "ALLOW_ANONYMIZATION=true" to the environment');
            return 0;
        }
        // Matches if either within the /var/www/octopustravelmatrix/ (all non-production servers), or running on a windows machine (My Dev Environment)
        if (!(str_starts_with(dirname(__FILE__), '/var/www/octopustravelmatrix/')
            || preg_match('/[A-Za-z]:\\.*/m', dirname(__FILE__)))) {
            $this->error('This command can only be run in a development/non-production environment');
            return 0;
        }
        $faker = Faker::create();
        foreach (Customer::withTrashed()->get() as $customer) {
            do {
                $succeeded = false;
                try {
                    $customer->update([
                        'title' => $faker->title,
                        'first_name' => $faker->firstName,
                        'middle_names' => $faker->firstName,
                        'last_name' => $faker->lastName,
                        'mobile_number' => $faker->phoneNumber,
                        'email_address' => isset($customer->email_address) ? $faker->email : null,
                        'passport_number' => isset($customer->passport_number) ? 123456 : null,
                        'password' => isset($customer->password) ? '$2a$12$4nlbfjMtXBPbvy0AjK3BH.owxxpVPVJlDk1TBN2X7eV7pKGqTa2D6' : null, // password
                        'passport_first_name' => $faker->firstName,
                        'passport_middle_name' => $faker->firstName,
                        'passport_last_name' => $faker->lastName,
                        'emergency_contact_name' => $faker->firstName . ' ' . $faker->lastName,
                        'emergency_contact_telephone' => $faker->phoneNumber,
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
