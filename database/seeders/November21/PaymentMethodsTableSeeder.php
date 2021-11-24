<?php

namespace Database\Seeders\November21;
use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('payment_methods')->delete();

        \DB::table('payment_methods')->insert(array (
            0 =>
                array (
                    'created_at' => '2021-09-07 13:03:55',
                    'deleted_at' => NULL,
                    'id' => 1,
                    'name' => 'Stripe',
                    'updated_at' => '2021-09-07 13:03:55',
                ),
            1 =>
                array (
                    'created_at' => '2021-09-07 13:04:01',
                    'deleted_at' => NULL,
                    'id' => 2,
                    'name' => 'PayPal',
                    'updated_at' => '2021-09-07 13:04:01',
                ),
            2 =>
                array (
                    'created_at' => '2021-09-07 13:04:11',
                    'deleted_at' => NULL,
                    'id' => 3,
                    'name' => 'BACS',
                    'updated_at' => '2021-09-07 13:04:11',
                ),
            3 =>
                array (
                    'created_at' => '2021-09-07 13:04:17',
                    'deleted_at' => NULL,
                    'id' => 4,
                    'name' => 'Cash',
                    'updated_at' => '2021-09-07 13:04:17',
                ),
            4 =>
                array (
                    'created_at' => '2021-09-07 13:04:23',
                    'deleted_at' => NULL,
                    'id' => 5,
                    'name' => 'Cheque',
                    'updated_at' => '2021-09-07 13:04:23',
                ),
            5 =>
                array (
                    'created_at' => '2021-09-07 13:04:33',
                    'deleted_at' => NULL,
                    'id' => 6,
                    'name' => 'Debit Card by Phone',
                    'updated_at' => '2021-09-07 13:04:33',
                ),
            6 =>
                array (
                    'created_at' => '2021-09-07 13:04:39',
                    'deleted_at' => NULL,
                    'id' => 7,
                    'name' => 'Credit Card by Phone',
                    'updated_at' => '2021-09-07 13:04:39',
                ),
        ));
    }
}
