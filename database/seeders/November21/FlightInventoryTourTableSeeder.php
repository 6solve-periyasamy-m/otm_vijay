<?php

namespace Database\Seeders\November21;

use Illuminate\Database\Seeder;

class FlightInventoryTourTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('flight_inventory_tours')->delete();
        
        \DB::table('flight_inventory_tours')->insert(array (
            0 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 14,
                'created_at' => NULL,
                'updated_at' => '2021-03-06 18:13:37',
                'deleted_at' => NULL,
                'id' => 9,
                'tour_sales_price' => 1499.0,
                'flight_type' => 'Inbound',
            ),
            1 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 14,
                'created_at' => NULL,
                'updated_at' => '2021-03-24 08:39:16',
                'deleted_at' => NULL,
                'id' => 10,
                'tour_sales_price' => NULL,
                'flight_type' => 'Inbound',
            ),
            2 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 15,
                'created_at' => NULL,
                'updated_at' => '2021-03-24 08:39:30',
                'deleted_at' => NULL,
                'id' => 11,
                'tour_sales_price' => NULL,
                'flight_type' => 'Outbound',
            ),
            3 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 16,
                'created_at' => NULL,
                'updated_at' => '2021-03-24 08:39:00',
                'deleted_at' => NULL,
                'id' => 12,
                'tour_sales_price' => NULL,
                'flight_type' => 'Outbound',
            ),
            4 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 18,
                'created_at' => NULL,
                'updated_at' => '2021-03-06 18:15:15',
                'deleted_at' => NULL,
                'id' => 13,
                'tour_sales_price' => NULL,
                'flight_type' => 'Inbound',
            ),
            5 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 19,
                'created_at' => NULL,
                'updated_at' => '2021-03-24 08:38:45',
                'deleted_at' => NULL,
                'id' => 14,
                'tour_sales_price' => NULL,
                'flight_type' => 'Outbound',
            ),
            6 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 17,
                'created_at' => NULL,
                'updated_at' => '2021-03-06 18:15:01',
                'deleted_at' => NULL,
                'id' => 15,
                'tour_sales_price' => NULL,
                'flight_type' => 'Outbound',
            ),
            7 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 17,
                'created_at' => NULL,
                'updated_at' => '2021-03-06 18:14:48',
                'deleted_at' => NULL,
                'id' => 16,
                'tour_sales_price' => NULL,
                'flight_type' => 'Outbound',
            ),
            8 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 16,
                'created_at' => NULL,
                'updated_at' => '2021-03-24 08:43:12',
                'deleted_at' => NULL,
                'id' => 17,
                'tour_sales_price' => 1000.0,
                'flight_type' => 'Outbound',
            ),
            9 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 20,
                'created_at' => NULL,
                'updated_at' => '2021-03-06 18:14:21',
                'deleted_at' => NULL,
                'id' => 18,
                'tour_sales_price' => NULL,
                'flight_type' => 'Outbound',
            ),
            10 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 21,
                'created_at' => NULL,
                'updated_at' => '2021-03-06 18:14:08',
                'deleted_at' => NULL,
                'id' => 19,
                'tour_sales_price' => NULL,
                'flight_type' => 'Outbound',
            ),
            11 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 21,
                'created_at' => NULL,
                'updated_at' => '2021-03-06 18:13:50',
                'deleted_at' => NULL,
                'id' => 20,
                'tour_sales_price' => NULL,
                'flight_type' => 'Outbound',
            ),
            12 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 22,
                'created_at' => NULL,
                'updated_at' => '2021-03-06 18:13:20',
                'deleted_at' => NULL,
                'id' => 23,
                'tour_sales_price' => NULL,
                'flight_type' => 'Outbound',
            ),
            13 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 26,
                'created_at' => NULL,
                'updated_at' => '2021-03-24 08:28:35',
                'deleted_at' => NULL,
                'id' => 24,
                'tour_sales_price' => NULL,
                'flight_type' => 'Outbound',
            ),
            14 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 27,
                'created_at' => NULL,
                'updated_at' => '2021-03-08 08:53:58',
                'deleted_at' => NULL,
                'id' => 25,
                'tour_sales_price' => NULL,
                'flight_type' => 'Outbound',
            ),
            15 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 28,
                'created_at' => NULL,
                'updated_at' => '2021-03-08 08:50:09',
                'deleted_at' => NULL,
                'id' => 26,
                'tour_sales_price' => NULL,
                'flight_type' => 'Outbound',
            ),
            16 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 28,
                'created_at' => NULL,
                'updated_at' => '2021-03-24 09:03:25',
                'deleted_at' => NULL,
                'id' => 27,
                'tour_sales_price' => NULL,
                'flight_type' => 'Outbound',
            ),
            17 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 29,
                'created_at' => NULL,
                'updated_at' => '2021-03-08 08:54:32',
                'deleted_at' => NULL,
                'id' => 28,
                'tour_sales_price' => 500.0,
                'flight_type' => 'Inbound',
            ),
            18 => 
            array (
                'tour_id' => 1,
                'flight_inventory_id' => 29,
                'created_at' => NULL,
                'updated_at' => '2021-03-08 08:51:40',
                'deleted_at' => NULL,
                'id' => 29,
                'tour_sales_price' => NULL,
                'flight_type' => 'Inbound',
            ),
        ));
        
        
    }
}
