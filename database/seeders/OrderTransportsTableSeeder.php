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
                'id' => 1,
                'order_customer_id' => 1,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'order_customer_id' => 2,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'order_customer_id' => 3,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'order_customer_id' => 4,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:38:49',
                'updated_at' => '2022-01-23 13:38:49',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'order_customer_id' => 5,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
