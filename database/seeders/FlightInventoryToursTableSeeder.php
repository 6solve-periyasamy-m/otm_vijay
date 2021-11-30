<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FlightInventoryToursTableSeeder extends Seeder
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
                'id' => 1,
                'tour_id' => 1,
                'flight_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 75.0,
                'flight_type' => 'Inbound',
                'created_at' => '2021-11-22 13:33:04',
                'updated_at' => '2021-11-22 13:40:17',
                'deleted_at' => '2021-11-22 13:40:17',
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'flight_inventory_id' => 2,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => 150.0,
                'flight_type' => 'Inbound',
                'created_at' => '2021-11-22 13:33:10',
                'updated_at' => '2021-11-22 13:40:09',
                'deleted_at' => '2021-11-22 13:40:09',
            ),
            2 => 
            array (
                'id' => 3,
                'tour_id' => 1,
                'flight_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 75.0,
                'flight_type' => 'Inbound',
                'created_at' => '2021-11-22 13:45:58',
                'updated_at' => '2021-11-30 11:56:08',
                'deleted_at' => '2021-11-30 11:56:08',
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 1,
                'flight_inventory_id' => 2,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => 150.0,
                'flight_type' => 'Inbound',
                'created_at' => '2021-11-22 13:46:04',
                'updated_at' => '2021-11-30 11:56:03',
                'deleted_at' => '2021-11-30 11:56:03',
            ),
            4 => 
            array (
                'id' => 5,
                'tour_id' => 1,
                'flight_inventory_id' => 9,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 100.0,
                'flight_type' => 'Outbound',
                'created_at' => '2021-11-30 12:16:57',
                'updated_at' => '2021-11-30 12:16:57',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'tour_id' => 1,
                'flight_inventory_id' => 10,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 100.0,
                'flight_type' => 'Outbound',
                'created_at' => '2021-11-30 12:16:57',
                'updated_at' => '2021-11-30 12:16:57',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'tour_id' => 1,
                'flight_inventory_id' => 11,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 100.0,
                'flight_type' => 'Outbound',
                'created_at' => '2021-11-30 12:16:57',
                'updated_at' => '2021-11-30 12:16:57',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'tour_id' => 1,
                'flight_inventory_id' => 12,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 150.0,
                'flight_type' => 'Outbound',
                'created_at' => '2021-11-30 12:16:57',
                'updated_at' => '2021-11-30 12:16:57',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'tour_id' => 1,
                'flight_inventory_id' => 7,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 100.0,
                'flight_type' => 'Outbound',
                'created_at' => '2021-11-30 12:16:57',
                'updated_at' => '2021-11-30 12:16:57',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'tour_id' => 1,
                'flight_inventory_id' => 8,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 100.0,
                'flight_type' => 'Outbound',
                'created_at' => '2021-11-30 12:16:57',
                'updated_at' => '2021-11-30 12:16:57',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'tour_id' => 1,
                'flight_inventory_id' => 5,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 100.0,
                'flight_type' => 'Outbound',
                'created_at' => '2021-11-30 12:16:57',
                'updated_at' => '2021-11-30 12:16:57',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'tour_id' => 1,
                'flight_inventory_id' => 6,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 150.0,
                'flight_type' => 'Outbound',
                'created_at' => '2021-11-30 12:16:57',
                'updated_at' => '2021-11-30 12:16:57',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
