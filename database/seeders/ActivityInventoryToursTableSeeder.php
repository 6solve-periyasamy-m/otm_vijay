<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityInventoryToursTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('activity_inventory_tours')->delete();
        DB::table('activity_inventory_tours')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tour_id' => 1,
                'activity_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 20.0,
                'created_at' => '2021-11-22 13:32:59',
                'updated_at' => '2021-11-22 13:40:03',
                'deleted_at' => '2021-11-22 13:40:03',
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'activity_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 20.0,
                'created_at' => '2021-11-22 13:45:50',
                'updated_at' => '2021-11-22 13:45:50',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'tour_id' => 2,
                'activity_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 20.0,
                'created_at' => '2021-11-22 13:45:50',
                'updated_at' => '2021-11-22 13:45:50',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 2,
                'activity_inventory_id' => 4,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 200.0,
                'created_at' => '2021-11-22 13:45:50',
                'updated_at' => '2021-11-22 13:45:50',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'tour_id' => 2,
                'activity_inventory_id' => 2,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 20.0,
                'created_at' => '2021-11-22 13:45:50',
                'updated_at' => '2021-11-22 13:45:50',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'tour_id' => 2,
                'activity_inventory_id' => 3,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 200.0,
                'created_at' => '2021-11-22 13:45:50',
                'updated_at' => '2021-11-22 13:45:50',
                'deleted_at' => NULL,
            ),
        ));
    }
}
