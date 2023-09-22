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
                'tour_sales_price' => '25.00',
                'created_at' => '2022-01-21 11:56:20',
                'updated_at' => '2022-01-21 11:56:20',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'activity_inventory_id' => 7,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'created_at' => '2022-01-21 11:56:20',
                'updated_at' => '2022-07-19 08:52:16',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'tour_id' => 1,
                'activity_inventory_id' => 8,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '250.00',
                'created_at' => '2022-01-21 11:56:20',
                'updated_at' => '2022-01-21 11:56:20',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 1,
                'activity_inventory_id' => 4,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '40.00',
                'created_at' => '2022-01-21 11:56:20',
                'updated_at' => '2022-01-21 11:56:20',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            4 => 
            array (
                'id' => 5,
                'tour_id' => 1,
                'activity_inventory_id' => 6,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => '60.00',
                'created_at' => '2022-01-21 11:56:31',
                'updated_at' => '2022-01-21 11:56:31',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            5 => 
            array (
                'id' => 6,
                'tour_id' => 1,
                'activity_inventory_id' => 2,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => '50.00',
                'created_at' => '2022-01-21 12:06:31',
                'updated_at' => '2022-01-21 12:06:31',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            6 => 
            array (
                'id' => 7,
                'tour_id' => 1,
                'activity_inventory_id' => 3,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => '75.00',
                'created_at' => '2022-01-21 12:06:50',
                'updated_at' => '2022-07-19 08:36:17',
                'deleted_at' => NULL,
                'is_bookable' => 0,
                'stock_control_active' => 1,
            ),
            7 => 
            array (
                'id' => 8,
                'tour_id' => 1,
                'activity_inventory_id' => 5,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => '150.00',
                'created_at' => '2022-01-21 12:07:07',
                'updated_at' => '2022-07-19 08:36:01',
                'deleted_at' => NULL,
                'is_bookable' => 0,
                'stock_control_active' => 1,
            ),
            8 => 
            array (
                'id' => 9,
                'tour_id' => 2,
                'activity_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '25.00',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:51:40',
                'deleted_at' => '2022-09-14 09:51:40',
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            9 => 
            array (
                'id' => 10,
                'tour_id' => 2,
                'activity_inventory_id' => 2,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => '50.00',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:51:43',
                'deleted_at' => '2022-09-14 09:51:43',
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            10 => 
            array (
                'id' => 11,
                'tour_id' => 2,
                'activity_inventory_id' => 7,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:51:32',
                'deleted_at' => '2022-09-14 09:51:32',
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            11 => 
            array (
                'id' => 12,
                'tour_id' => 2,
                'activity_inventory_id' => 8,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '250.00',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:51:36',
                'deleted_at' => '2022-09-14 09:51:36',
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            12 => 
            array (
                'id' => 13,
                'tour_id' => 2,
                'activity_inventory_id' => 4,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '40.00',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:51:24',
                'deleted_at' => '2022-09-14 09:51:24',
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            13 => 
            array (
                'id' => 14,
                'tour_id' => 2,
                'activity_inventory_id' => 5,
                'tour_component_type' => 'Upgrade',
                'tour_sales_price' => '150.00',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:51:28',
                'deleted_at' => '2022-09-14 09:51:28',
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            14 => 
            array (
                'id' => 15,
                'tour_id' => 2,
                'activity_inventory_id' => 6,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => '60.00',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:51:18',
                'deleted_at' => '2022-09-14 09:51:18',
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            15 => 
            array (
                'id' => 16,
                'tour_id' => 2,
                'activity_inventory_id' => 3,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => '75.00',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:51:47',
                'deleted_at' => '2022-09-14 09:51:47',
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            16 => 
            array (
                'id' => 17,
                'tour_id' => 2,
                'activity_inventory_id' => 9,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '350.00',
                'created_at' => '2022-09-14 10:17:43',
                'updated_at' => '2022-09-14 10:17:43',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            17 => 
            array (
                'id' => 18,
                'tour_id' => 2,
                'activity_inventory_id' => 10,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '360.00',
                'created_at' => '2022-09-14 10:17:43',
                'updated_at' => '2022-09-14 10:17:43',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            18 => 
            array (
                'id' => 19,
                'tour_id' => 2,
                'activity_inventory_id' => 11,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => '360.00',
                'created_at' => '2022-09-14 12:18:39',
                'updated_at' => '2022-09-14 12:18:39',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            19 => 
            array (
                'id' => 20,
                'tour_id' => 4,
                'activity_inventory_id' => 9,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '350.00',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            20 => 
            array (
                'id' => 21,
                'tour_id' => 4,
                'activity_inventory_id' => 10,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '360.00',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            21 => 
            array (
                'id' => 22,
                'tour_id' => 5,
                'activity_inventory_id' => 12,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => '150.00',
                'created_at' => '2022-09-20 19:16:53',
                'updated_at' => '2022-09-20 19:19:15',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            22 => 
            array (
                'id' => 23,
                'tour_id' => 6,
                'activity_inventory_id' => 13,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '75.00',
                'created_at' => '2022-09-21 09:03:19',
                'updated_at' => '2022-09-21 09:03:19',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            23 => 
            array (
                'id' => 24,
                'tour_id' => 7,
                'activity_inventory_id' => 14,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '25.00',
                'created_at' => '2023-09-21 14:07:55',
                'updated_at' => '2023-09-21 14:07:55',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            24 => 
            array (
                'id' => 25,
                'tour_id' => 7,
                'activity_inventory_id' => 17,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '30.00',
                'created_at' => '2023-09-21 14:07:55',
                'updated_at' => '2023-09-21 14:07:55',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            25 => 
            array (
                'id' => 26,
                'tour_id' => 7,
                'activity_inventory_id' => 15,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => '50.00',
                'created_at' => '2023-09-21 14:08:08',
                'updated_at' => '2023-09-21 14:08:08',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
            26 => 
            array (
                'id' => 27,
                'tour_id' => 7,
                'activity_inventory_id' => 16,
                'tour_component_type' => 'Add-on',
                'tour_sales_price' => '450.00',
                'created_at' => '2023-09-21 14:08:08',
                'updated_at' => '2023-09-21 14:08:08',
                'deleted_at' => NULL,
                'is_bookable' => 1,
                'stock_control_active' => 1,
            ),
        ));
        
        
    }
}