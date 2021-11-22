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
                'flight_type' => NULL,
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
                'flight_type' => NULL,
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
                'flight_type' => NULL,
                'created_at' => '2021-11-22 13:45:58',
                'updated_at' => '2021-11-22 13:45:58',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 1,
                'flight_inventory_id' => 2,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => 150.0,
                'flight_type' => NULL,
                'created_at' => '2021-11-22 13:46:04',
                'updated_at' => '2021-11-22 13:46:04',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}