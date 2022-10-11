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
                'tour_sales_price' => '50.00',
                'flight_type' => 'Outbound',
                'created_at' => '2022-01-21 12:10:32',
                'updated_at' => '2022-01-21 12:11:35',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'flight_inventory_id' => 4,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'flight_type' => 'Inbound',
                'created_at' => '2022-01-21 12:10:41',
                'updated_at' => '2022-01-21 12:10:41',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'tour_id' => 1,
                'flight_inventory_id' => 3,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => '150.00',
                'flight_type' => 'Outbound',
                'created_at' => '2022-01-21 12:10:51',
                'updated_at' => '2022-01-21 12:10:51',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 1,
                'flight_inventory_id' => 6,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => '100.00',
                'flight_type' => 'Inbound',
                'created_at' => '2022-01-21 12:11:21',
                'updated_at' => '2022-01-21 12:11:21',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'tour_id' => 1,
                'flight_inventory_id' => 5,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => '250.00',
                'flight_type' => 'Inbound',
                'created_at' => '2022-01-21 12:12:00',
                'updated_at' => '2022-01-21 12:12:00',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'tour_id' => 1,
                'flight_inventory_id' => 2,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => '150.00',
                'flight_type' => 'Outbound',
                'created_at' => '2022-01-21 12:12:24',
                'updated_at' => '2022-01-21 12:12:24',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'tour_id' => 1,
                'flight_inventory_id' => 7,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'flight_type' => 'Outbound',
                'created_at' => '2022-01-21 12:44:30',
                'updated_at' => '2022-01-21 12:44:30',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'tour_id' => 2,
                'flight_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'flight_type' => 'Outbound',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:52:00',
                'deleted_at' => '2022-09-14 09:52:00',
                'is_bookable' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'tour_id' => 2,
                'flight_inventory_id' => 2,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => '150.00',
                'flight_type' => 'Outbound',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:52:03',
                'deleted_at' => '2022-09-14 09:52:03',
                'is_bookable' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'tour_id' => 2,
                'flight_inventory_id' => 4,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'flight_type' => 'Inbound',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:51:51',
                'deleted_at' => '2022-09-14 09:51:51',
                'is_bookable' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'tour_id' => 2,
                'flight_inventory_id' => 5,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => '250.00',
                'flight_type' => 'Inbound',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:51:54',
                'deleted_at' => '2022-09-14 09:51:54',
                'is_bookable' => 1,
            ),
            11 => 
            array (
                'id' => 12,
                'tour_id' => 2,
                'flight_inventory_id' => 3,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => '150.00',
                'flight_type' => 'Outbound',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:52:07',
                'deleted_at' => '2022-09-14 09:52:07',
                'is_bookable' => 1,
            ),
            12 => 
            array (
                'id' => 13,
                'tour_id' => 2,
                'flight_inventory_id' => 6,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => '100.00',
                'flight_type' => 'Inbound',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:51:58',
                'deleted_at' => '2022-09-14 09:51:58',
                'is_bookable' => 1,
            ),
            13 => 
            array (
                'id' => 14,
                'tour_id' => 2,
                'flight_inventory_id' => 7,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'flight_type' => 'Outbound',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:52:10',
                'deleted_at' => '2022-09-14 09:52:10',
                'is_bookable' => 1,
            ),
            14 => 
            array (
                'id' => 15,
                'tour_id' => 2,
                'flight_inventory_id' => 8,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '974.00',
                'flight_type' => 'Outbound',
                'created_at' => '2022-09-14 10:21:24',
                'updated_at' => '2022-09-14 10:21:24',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            15 => 
            array (
                'id' => 16,
                'tour_id' => 2,
                'flight_inventory_id' => 9,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '850.00',
                'flight_type' => 'Inbound',
                'created_at' => '2022-09-14 10:21:32',
                'updated_at' => '2022-09-14 10:21:32',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            16 => 
            array (
                'id' => 17,
                'tour_id' => 4,
                'flight_inventory_id' => 8,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '974.00',
                'flight_type' => NULL,
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            17 => 
            array (
                'id' => 18,
                'tour_id' => 4,
                'flight_inventory_id' => 9,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '850.00',
                'flight_type' => NULL,
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            18 => 
            array (
                'id' => 19,
                'tour_id' => 5,
                'flight_inventory_id' => 11,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '400.00',
                'flight_type' => 'Outbound',
                'created_at' => '2022-09-20 18:49:05',
                'updated_at' => '2022-09-20 18:49:05',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
            19 => 
            array (
                'id' => 20,
                'tour_id' => 5,
                'flight_inventory_id' => 12,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '495.00',
                'flight_type' => 'Inbound',
                'created_at' => '2022-09-20 18:49:05',
                'updated_at' => '2022-09-20 19:21:27',
                'deleted_at' => NULL,
                'is_bookable' => 1,
            ),
        ));
        
        
    }
}