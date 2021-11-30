<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderFlightsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_flights')->delete();
        
        \DB::table('order_flights')->insert(array (
            0 => 
            array (
                'id' => 2,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 5,
                'created_at' => '2021-11-30 12:35:41',
                'updated_at' => '2021-11-30 12:35:41',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 3,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 6,
                'created_at' => '2021-11-30 12:35:41',
                'updated_at' => '2021-11-30 12:35:41',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 4,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 7,
                'created_at' => '2021-11-30 12:35:41',
                'updated_at' => '2021-11-30 12:35:41',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 5,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 8,
                'created_at' => '2021-11-30 12:35:41',
                'updated_at' => '2021-11-30 12:35:41',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 6,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 9,
                'created_at' => '2021-11-30 12:35:41',
                'updated_at' => '2021-11-30 12:35:41',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 7,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 10,
                'created_at' => '2021-11-30 12:35:41',
                'updated_at' => '2021-11-30 12:35:41',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 8,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 11,
                'created_at' => '2021-11-30 12:35:41',
                'updated_at' => '2021-11-30 12:35:41',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 9,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 12,
                'created_at' => '2021-11-30 12:35:41',
                'updated_at' => '2021-11-30 12:35:41',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
