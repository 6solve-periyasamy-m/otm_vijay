<?php

namespace Database\Seeders;

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
                'id' => 1,
                'name' => 'Stripe',
                'deleted_at' => NULL,
                'created_at' => '2021-09-07 13:03:55',
                'updated_at' => '2021-09-07 13:03:55',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'PayPal',
                'deleted_at' => NULL,
                'created_at' => '2021-09-07 13:04:01',
                'updated_at' => '2021-09-07 13:04:01',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'BACS',
                'deleted_at' => NULL,
                'created_at' => '2021-09-07 13:04:11',
                'updated_at' => '2021-09-07 13:04:11',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Cash',
                'deleted_at' => NULL,
                'created_at' => '2021-09-07 13:04:17',
                'updated_at' => '2021-09-07 13:04:17',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Cheque',
                'deleted_at' => NULL,
                'created_at' => '2021-09-07 13:04:23',
                'updated_at' => '2021-09-07 13:04:23',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Debit Card by Phone',
                'deleted_at' => NULL,
                'created_at' => '2021-09-07 13:04:33',
                'updated_at' => '2021-09-07 13:04:33',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Credit Card by Phone',
                'deleted_at' => NULL,
                'created_at' => '2021-09-07 13:04:39',
                'updated_at' => '2021-09-07 13:04:39',
            ),
        ));
        
        
    }
}
