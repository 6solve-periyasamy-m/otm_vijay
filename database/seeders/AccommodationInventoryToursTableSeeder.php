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
                'tour_sales_price' => 45.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 12:51:19',
                'updated_at' => '2022-01-20 12:51:19',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'accommodation_inventory_id' => 7,
                'tour_sales_price' => 45.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 12:51:19',
                'updated_at' => '2022-01-20 12:51:19',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'tour_id' => 1,
                'accommodation_inventory_id' => 11,
                'tour_sales_price' => 45.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 12:51:19',
                'updated_at' => '2022-01-20 12:51:19',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 1,
                'accommodation_inventory_id' => 15,
                'tour_sales_price' => 45.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 12:51:19',
                'updated_at' => '2022-01-20 12:51:19',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'tour_id' => 1,
                'accommodation_inventory_id' => 19,
                'tour_sales_price' => 45.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 12:51:19',
                'updated_at' => '2022-01-20 12:51:19',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'tour_id' => 1,
                'accommodation_inventory_id' => 23,
                'tour_sales_price' => 45.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 12:51:19',
                'updated_at' => '2022-01-20 12:51:19',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'tour_id' => 1,
                'accommodation_inventory_id' => 27,
                'tour_sales_price' => 45.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 12:51:19',
                'updated_at' => '2022-01-20 12:51:19',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'tour_id' => 1,
                'accommodation_inventory_id' => 31,
                'tour_sales_price' => 45.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 12:51:19',
                'updated_at' => '2022-01-20 12:51:19',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'tour_id' => 1,
                'accommodation_inventory_id' => 35,
                'tour_sales_price' => 45.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 12:51:19',
                'updated_at' => '2022-01-20 12:51:19',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'tour_id' => 1,
                'accommodation_inventory_id' => 12,
                'tour_sales_price' => 20.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 12:56:34',
                'updated_at' => '2022-01-20 12:56:34',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'tour_id' => 1,
                'accommodation_inventory_id' => 9,
                'tour_sales_price' => 50.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:08:43',
                'updated_at' => '2022-01-20 13:08:43',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'tour_id' => 1,
                'accommodation_inventory_id' => 10,
                'tour_sales_price' => 100.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:09:17',
                'updated_at' => '2022-01-20 13:09:17',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'tour_id' => 1,
                'accommodation_inventory_id' => 8,
                'tour_sales_price' => 25.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:12:50',
                'updated_at' => '2022-01-20 13:12:50',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'tour_id' => 1,
                'accommodation_inventory_id' => 5,
                'tour_sales_price' => 50.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:13:03',
                'updated_at' => '2022-01-20 13:13:03',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'tour_id' => 1,
                'accommodation_inventory_id' => 6,
                'tour_sales_price' => 75.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:13:16',
                'updated_at' => '2022-01-20 13:13:16',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'tour_id' => 1,
                'accommodation_inventory_id' => 4,
                'tour_sales_price' => 25.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:13:55',
                'updated_at' => '2022-01-20 13:13:55',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'tour_id' => 1,
                'accommodation_inventory_id' => 1,
                'tour_sales_price' => 50.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:14:07',
                'updated_at' => '2022-01-20 13:14:07',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'tour_id' => 1,
                'accommodation_inventory_id' => 2,
                'tour_sales_price' => 75.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:14:21',
                'updated_at' => '2022-01-20 13:14:21',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'tour_id' => 1,
                'accommodation_inventory_id' => 36,
                'tour_sales_price' => 25.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:14:57',
                'updated_at' => '2022-01-20 13:14:57',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'tour_id' => 1,
                'accommodation_inventory_id' => 33,
                'tour_sales_price' => 50.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:15:19',
                'updated_at' => '2022-01-20 13:15:19',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'tour_id' => 1,
                'accommodation_inventory_id' => 34,
                'tour_sales_price' => 75.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:15:28',
                'updated_at' => '2022-01-20 13:15:28',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'tour_id' => 1,
                'accommodation_inventory_id' => 32,
                'tour_sales_price' => 25.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:16:30',
                'updated_at' => '2022-01-20 13:16:30',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'tour_id' => 1,
                'accommodation_inventory_id' => 29,
                'tour_sales_price' => 50.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:16:44',
                'updated_at' => '2022-01-20 13:16:44',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'tour_id' => 1,
                'accommodation_inventory_id' => 30,
                'tour_sales_price' => 75.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:17:51',
                'updated_at' => '2022-01-20 13:17:51',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'tour_id' => 1,
                'accommodation_inventory_id' => 28,
                'tour_sales_price' => 25.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:18:20',
                'updated_at' => '2022-01-20 13:18:20',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'tour_id' => 1,
                'accommodation_inventory_id' => 25,
                'tour_sales_price' => 50.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:18:34',
                'updated_at' => '2022-01-20 13:18:34',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'tour_id' => 1,
                'accommodation_inventory_id' => 26,
                'tour_sales_price' => 75.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:18:47',
                'updated_at' => '2022-01-20 13:18:47',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'tour_id' => 1,
                'accommodation_inventory_id' => 24,
                'tour_sales_price' => 25.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:19:12',
                'updated_at' => '2022-01-20 13:19:12',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'tour_id' => 1,
                'accommodation_inventory_id' => 21,
                'tour_sales_price' => 50.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:19:23',
                'updated_at' => '2022-01-20 13:19:23',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'tour_id' => 1,
                'accommodation_inventory_id' => 22,
                'tour_sales_price' => 75.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:19:33',
                'updated_at' => '2022-01-20 13:19:33',
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'tour_id' => 1,
                'accommodation_inventory_id' => 20,
                'tour_sales_price' => 25.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:22:35',
                'updated_at' => '2022-01-20 13:22:35',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'tour_id' => 1,
                'accommodation_inventory_id' => 17,
                'tour_sales_price' => 50.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:22:46',
                'updated_at' => '2022-01-20 13:22:46',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'tour_id' => 1,
                'accommodation_inventory_id' => 18,
                'tour_sales_price' => 75.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:22:57',
                'updated_at' => '2022-01-20 13:22:57',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'tour_id' => 1,
                'accommodation_inventory_id' => 16,
                'tour_sales_price' => 25.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:23:35',
                'updated_at' => '2022-01-20 13:23:35',
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'tour_id' => 1,
                'accommodation_inventory_id' => 13,
                'tour_sales_price' => 50.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:23:47',
                'updated_at' => '2022-01-20 13:23:47',
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'tour_id' => 1,
                'accommodation_inventory_id' => 14,
                'tour_sales_price' => 75.0,
                'tour_component_type' => 'Upgrade',
                'booking_policy' => 'overbook',
                'created_at' => '2022-01-20 13:24:00',
                'updated_at' => '2022-01-20 13:24:00',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
