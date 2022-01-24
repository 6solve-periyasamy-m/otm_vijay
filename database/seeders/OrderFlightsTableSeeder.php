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
                'id' => 1,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'order_customer_id' => 2,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'order_customer_id' => 2,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'order_customer_id' => 2,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'order_customer_id' => 3,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'order_customer_id' => 3,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'order_customer_id' => 3,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'order_customer_id' => 4,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:38:49',
                'updated_at' => '2022-01-23 13:38:49',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'order_customer_id' => 4,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:38:49',
                'updated_at' => '2022-01-23 13:38:49',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'order_customer_id' => 4,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:38:49',
                'updated_at' => '2022-01-23 13:38:49',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'order_customer_id' => 5,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'order_customer_id' => 5,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'order_customer_id' => 5,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
