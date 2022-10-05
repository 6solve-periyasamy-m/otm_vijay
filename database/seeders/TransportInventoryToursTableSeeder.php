<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TransportInventoryToursTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('transport_inventory_tours')->delete();
        
        \DB::table('transport_inventory_tours')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tour_id' => 1,
                'transport_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'created_at' => '2022-01-21 12:44:34',
                'updated_at' => '2022-01-21 12:44:34',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 2,
                'transport_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:52:15',
                'deleted_at' => '2022-09-14 09:52:15',
                'is_bookable' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'tour_id' => 2,
                'transport_inventory_id' => 2,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '15.00',
                'created_at' => '2022-09-14 10:49:00',
                'updated_at' => '2022-09-14 10:49:00',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 4,
                'transport_inventory_id' => 2,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '15.00',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'tour_id' => 5,
                'transport_inventory_id' => 3,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '10.00',
                'created_at' => '2022-09-20 19:10:55',
                'updated_at' => '2022-09-20 19:10:55',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'tour_id' => 5,
                'transport_inventory_id' => 4,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '10.00',
                'created_at' => '2022-09-20 19:10:55',
                'updated_at' => '2022-09-20 19:10:55',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'tour_id' => 6,
                'transport_inventory_id' => 5,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '30.00',
                'created_at' => '2022-09-21 09:03:24',
                'updated_at' => '2022-09-21 09:03:24',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'tour_id' => 6,
                'transport_inventory_id' => 6,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '35.00',
                'created_at' => '2022-09-21 09:04:43',
                'updated_at' => '2022-09-21 09:04:43',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
        ));
        
        
    }
}