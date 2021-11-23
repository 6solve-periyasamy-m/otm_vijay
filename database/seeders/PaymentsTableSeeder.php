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
                'payment_method_id' => 2,
                'amount' => 150.0,
                'paid_on' => '2021-11-22 00:00:00',
                'payment_type' => 'Deposit',
                'deleted_at' => '2021-11-22 14:50:25',
                'created_at' => '2021-11-22 13:52:42',
                'updated_at' => '2021-11-22 14:50:25',
            ),
            1 => 
            array (
                'id' => 2,
                'order_id' => 1,
                'payment_method_id' => 2,
                'amount' => 150.0,
                'paid_on' => '2021-11-22 14:50:00',
                'payment_type' => 'Deposit',
                'deleted_at' => NULL,
                'created_at' => '2021-11-22 14:50:41',
                'updated_at' => '2021-11-22 14:50:41',
            ),
        ));
        
        
    }
}
