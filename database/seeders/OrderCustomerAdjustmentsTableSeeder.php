<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderCustomerAdjustmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_customer_adjustments')->delete();
        
        \DB::table('order_customer_adjustments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_customer_id' => 1,
                'amount' => -20.0,
                'reason' => 'Customer Discount: Repeat',
                'date' => '2021-11-22',
                'deleted_at' => '2021-11-22 14:51:09',
                'created_at' => '2021-11-22 14:24:13',
                'updated_at' => '2021-11-22 14:51:09',
            ),
        ));
        
        
    }
}
