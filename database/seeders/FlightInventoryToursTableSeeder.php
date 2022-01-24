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
                'tour_sales_price' => 50.0,
                'flight_type' => 'Outbound',
                'created_at' => '2022-01-21 12:10:32',
                'updated_at' => '2022-01-21 12:11:35',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'flight_inventory_id' => 4,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 50.0,
                'flight_type' => 'Inbound',
                'created_at' => '2022-01-21 12:10:41',
                'updated_at' => '2022-01-21 12:10:41',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'tour_id' => 1,
                'flight_inventory_id' => 3,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => 150.0,
                'flight_type' => 'Outbound',
                'created_at' => '2022-01-21 12:10:51',
                'updated_at' => '2022-01-21 12:10:51',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 1,
                'flight_inventory_id' => 6,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => 100.0,
                'flight_type' => 'Inbound',
                'created_at' => '2022-01-21 12:11:21',
                'updated_at' => '2022-01-21 12:11:21',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'tour_id' => 1,
                'flight_inventory_id' => 5,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => 250.0,
                'flight_type' => 'Inbound',
                'created_at' => '2022-01-21 12:12:00',
                'updated_at' => '2022-01-21 12:12:00',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'tour_id' => 1,
                'flight_inventory_id' => 2,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => 150.0,
                'flight_type' => 'Outbound',
                'created_at' => '2022-01-21 12:12:24',
                'updated_at' => '2022-01-21 12:12:24',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'tour_id' => 1,
                'flight_inventory_id' => 7,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 50.0,
                'flight_type' => 'Outbound',
                'created_at' => '2022-01-21 12:44:30',
                'updated_at' => '2022-01-21 12:44:30',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
