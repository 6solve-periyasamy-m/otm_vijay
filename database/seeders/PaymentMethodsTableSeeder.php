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
                'created_at' => '2021-09-07 13:03:55',
                'updated_at' => '2021-09-07 13:03:55',
            ),
            1 => 
            array (
                'id' => 3,
                'name' => 'BACS/Direct Deposit',
                'created_at' => '2021-09-07 13:04:11',
                'updated_at' => '2024-07-17 14:28:11',
            ),
            2 => 
            array (
                'id' => 4,
                'name' => 'Cash',
                'created_at' => '2021-09-07 13:04:17',
                'updated_at' => '2021-09-07 13:04:17',
            ),
            3 => 
            array (
                'id' => 5,
                'name' => 'Cheque',
                'created_at' => '2021-09-07 13:04:23',
                'updated_at' => '2021-09-07 13:04:23',
            ),
            4 => 
            array (
                'id' => 6,
                'name' => 'Debit Card',
                'created_at' => '2021-09-07 13:04:33',
                'updated_at' => '2024-07-17 14:28:39',
            ),
            5 => 
            array (
                'id' => 9,
                'name' => 'Airwallex',
                'created_at' => '2024-07-16 12:45:55',
                'updated_at' => '2024-07-16 12:45:55',
            ),
            6 => 
            array (
                'id' => 10,
                'name' => 'Felloh',
                'created_at' => '2024-07-17 14:27:43',
                'updated_at' => '2024-07-17 14:27:43',
            ),
            7 => 
            array (
                'id' => 11,
                'name' => 'Credit Card',
                'created_at' => '2024-07-17 14:27:49',
                'updated_at' => '2024-07-17 14:27:49',
            ),
            8 => 
            array (
                'id' => 12,
                'name' => 'Opayo',
                'created_at' => '2024-07-17 14:28:25',
                'updated_at' => '2024-07-17 14:28:25',
            ),
        ));
        
        
    }
}