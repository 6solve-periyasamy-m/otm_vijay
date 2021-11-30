<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderTransportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_transports')->delete();
        
        \DB::table('order_transports')->insert(array (
            0 => 
            array (
                'id' => 2,
                'order_customer_id' => 1,
                'transport_inventory_tour_id' => 4,
                'created_at' => '2021-11-30 12:35:41',
                'updated_at' => '2021-11-30 12:35:41',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 3,
                'order_customer_id' => 1,
                'transport_inventory_tour_id' => 5,
                'created_at' => '2021-11-30 12:35:41',
                'updated_at' => '2021-11-30 12:35:41',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 4,
                'order_customer_id' => 1,
                'transport_inventory_tour_id' => 6,
                'created_at' => '2021-11-30 12:35:41',
                'updated_at' => '2021-11-30 12:35:41',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
