<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payments')->delete();
        
        \DB::table('payments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_id' => 1,
                'payment_method_id' => 1,
                'customer_id' => 1,
                'amount' => 300.0,
                'paid_on' => '2022-01-23 01:00:00',
                'payment_type' => 'Deposit',
                'deleted_at' => NULL,
                'created_at' => '2022-01-23 13:18:05',
                'updated_at' => '2022-01-23 13:18:05',
            ),
            1 => 
            array (
                'id' => 2,
                'order_id' => 2,
                'payment_method_id' => 3,
                'customer_id' => 2,
                'amount' => 3498.0,
                'paid_on' => '2022-01-23 00:00:00',
                'payment_type' => 'Deposit',
                'deleted_at' => NULL,
                'created_at' => '2022-01-23 13:21:35',
                'updated_at' => '2022-01-23 13:21:35',
            ),
            2 => 
            array (
                'id' => 6,
                'order_id' => 1,
                'payment_method_id' => 4,
                'customer_id' => 1,
                'amount' => 1025.0,
                'paid_on' => '2022-01-10 20:22:00',
                'payment_type' => 'Installment',
                'deleted_at' => NULL,
                'created_at' => '2022-01-23 13:45:08',
                'updated_at' => '2022-01-23 13:45:23',
            ),
            3 => 
            array (
                'id' => 7,
                'order_id' => 4,
                'payment_method_id' => 1,
                'customer_id' => 5,
                'amount' => 300.0,
                'paid_on' => '2022-01-10 00:00:00',
                'payment_type' => 'Deposit',
                'deleted_at' => NULL,
                'created_at' => '2022-01-23 13:48:14',
                'updated_at' => '2022-01-23 13:48:14',
            ),
            4 => 
            array (
                'id' => 8,
                'order_id' => 4,
                'payment_method_id' => 1,
                'customer_id' => 5,
                'amount' => -300.0,
                'paid_on' => '2022-01-12 10:00:00',
                'payment_type' => 'Refund',
                'deleted_at' => NULL,
                'created_at' => '2022-01-23 13:48:39',
                'updated_at' => '2022-01-23 13:48:39',
            ),
        ));
        
        
    }
}
