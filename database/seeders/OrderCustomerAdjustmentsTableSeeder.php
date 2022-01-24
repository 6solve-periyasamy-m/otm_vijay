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
        
        
        
    }
}
