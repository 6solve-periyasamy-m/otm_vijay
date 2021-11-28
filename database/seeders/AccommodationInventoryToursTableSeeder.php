<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccommodationInventoryToursTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('accommodation_inventory_tours')->delete();
        
        \DB::table('accommodation_inventory_tours')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tour_id' => 1,
                'accommodation_inventory_id' => 3,
                'tour_sales_price' => 250.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2021-11-22 13:32:55',
                'updated_at' => '2021-11-22 13:36:11',
                'deleted_at' => '2021-11-22 13:36:11',
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'accommodation_inventory_id' => 3,
                'tour_sales_price' => 250.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2021-11-22 13:45:41',
                'updated_at' => '2021-11-22 13:45:41',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'tour_id' => 2,
                'accommodation_inventory_id' => 3,
                'tour_sales_price' => 280.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2021-11-22 13:45:41',
                'updated_at' => '2021-11-22 13:45:41',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 2,
                'accommodation_inventory_id' => 4,
                'tour_sales_price' => 260.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2021-11-22 13:45:41',
                'updated_at' => '2021-11-22 13:45:41',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'tour_id' => 2,
                'accommodation_inventory_id' => 4,
                'tour_sales_price' => 290.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2021-11-22 13:45:41',
                'updated_at' => '2021-11-22 13:45:41',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'tour_id' => 2,
                'accommodation_inventory_id' => 4,
                'tour_sales_price' => 200.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2021-11-22 13:45:41',
                'updated_at' => '2021-11-22 13:45:41',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'tour_id' => 2,
                'accommodation_inventory_id' => 5,
                'tour_sales_price' => 2590.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2021-11-22 13:45:41',
                'updated_at' => '2021-11-22 13:45:41',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'tour_id' => 2,
                'accommodation_inventory_id' => 6,
                'tour_sales_price' => 220.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2021-11-22 13:45:41',
                'updated_at' => '2021-11-22 13:45:41',
                'deleted_at' => NULL,
            ),
        ));
    }
}
