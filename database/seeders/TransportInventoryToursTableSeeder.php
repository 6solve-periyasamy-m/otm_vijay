<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportInventoryToursTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('transport_inventory_tours')->delete();
        DB::table('transport_inventory_tours')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tour_id' => 1,
                'transport_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 50.0,
                'created_at' => '2022-01-21 12:44:34',
                'updated_at' => '2022-01-21 12:44:34',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'tour_id' => 2,
                'transport_inventory_id' => 2,
                'tour_component_type' => 'Included',
                'tour_sales_price' => NULL,
                'created_at' => '2021-11-22 13:33:15',
                'updated_at' => '2021-11-22 13:40:21',
                'deleted_at' => '2021-11-22 13:40:21',
            ),
            7 => 
            array (
                'id' => 8,
                'tour_id' => 2,
                'transport_inventory_id' => 3,
                'tour_component_type' => 'Included',
                'tour_sales_price' => NULL,
                'created_at' => '2021-11-22 13:46:09',
                'updated_at' => '2021-11-30 12:13:11',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'tour_id' => 2,
                'transport_inventory_id' => 4,
                'tour_component_type' => 'Included',
                'tour_sales_price' => NULL,
                'created_at' => '2021-11-30 12:17:24',
                'updated_at' => '2021-11-30 12:21:57',
                'deleted_at' => '2021-11-30 12:21:57',
            ),
            9 => 
            array (
                'id' => 10,
                'tour_id' => 2,
                'transport_inventory_id' => 5,
                'tour_component_type' => 'Included',
                'tour_sales_price' => NULL,
                'created_at' => '2021-11-30 12:17:24',
                'updated_at' => '2021-11-30 12:17:24',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'tour_id' => 2,
                'transport_inventory_id' => 2,
                'tour_component_type' => 'Included',
                'tour_sales_price' => NULL,
                'created_at' => '2021-11-30 12:17:24',
                'updated_at' => '2021-11-30 12:17:24',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'tour_id' => 2,
                'transport_inventory_id' => 3,
                'tour_component_type' => 'Included',
                'tour_sales_price' => NULL,
                'created_at' => '2021-11-30 12:22:09',
                'updated_at' => '2021-11-30 12:22:09',
                'deleted_at' => NULL,
            ),
        ));
    }
}
