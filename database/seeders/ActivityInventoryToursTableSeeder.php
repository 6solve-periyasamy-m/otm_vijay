<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ActivityInventoryToursTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('activity_inventory_tours')->delete();
        
        \DB::table('activity_inventory_tours')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tour_id' => 1,
                'activity_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 25.0,
                'created_at' => '2022-01-21 11:56:20',
                'updated_at' => '2022-01-21 11:56:20',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'activity_inventory_id' => 7,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 50.0,
                'created_at' => '2022-01-21 11:56:20',
                'updated_at' => '2022-01-21 11:56:20',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'tour_id' => 1,
                'activity_inventory_id' => 8,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 250.0,
                'created_at' => '2022-01-21 11:56:20',
                'updated_at' => '2022-01-21 11:56:20',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 1,
                'activity_inventory_id' => 4,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 40.0,
                'created_at' => '2022-01-21 11:56:20',
                'updated_at' => '2022-01-21 11:56:20',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'tour_id' => 1,
                'activity_inventory_id' => 6,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => 60.0,
                'created_at' => '2022-01-21 11:56:31',
                'updated_at' => '2022-01-21 11:56:31',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'tour_id' => 1,
                'activity_inventory_id' => 2,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => 50.0,
                'created_at' => '2022-01-21 12:06:31',
                'updated_at' => '2022-01-21 12:06:31',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'tour_id' => 1,
                'activity_inventory_id' => 3,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => 75.0,
                'created_at' => '2022-01-21 12:06:50',
                'updated_at' => '2022-01-21 12:06:50',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'tour_id' => 1,
                'activity_inventory_id' => 5,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => 150.0,
                'created_at' => '2022-01-21 12:07:07',
                'updated_at' => '2022-01-21 12:07:07',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
