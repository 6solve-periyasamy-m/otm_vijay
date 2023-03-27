<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderTransportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_transports')->delete();
        
        \DB::table('order_transports')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_customer_id' => 1,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'order_customer_id' => 2,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'order_customer_id' => 3,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'order_customer_id' => 4,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:38:49',
                'updated_at' => '2022-01-23 13:38:49',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'order_customer_id' => 5,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'order_customer_id' => 6,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'order_customer_id' => 7,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'order_customer_id' => 8,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:24',
                'updated_at' => '2022-06-29 07:59:24',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'order_customer_id' => 9,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'order_customer_id' => 10,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:05',
                'updated_at' => '2022-06-29 08:21:05',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'order_customer_id' => 11,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'order_customer_id' => 12,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:09',
                'updated_at' => '2022-06-29 08:21:09',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'order_customer_id' => 13,
                'transport_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:11',
                'updated_at' => '2022-06-29 08:21:11',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'order_customer_id' => 14,
                'transport_inventory_tour_id' => 3,
                'cost' => '15.00',
                'created_at' => '2022-09-14 11:22:24',
                'updated_at' => '2022-09-14 11:22:24',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'order_customer_id' => 15,
                'transport_inventory_tour_id' => 3,
                'cost' => '15.00',
                'created_at' => '2022-09-14 11:22:24',
                'updated_at' => '2022-09-14 11:22:24',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'order_customer_id' => 16,
                'transport_inventory_tour_id' => 4,
                'cost' => '15.00',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'order_customer_id' => 17,
                'transport_inventory_tour_id' => 4,
                'cost' => '15.00',
                'created_at' => '2022-09-20 10:39:18',
                'updated_at' => '2022-09-20 10:39:18',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'order_customer_id' => 18,
                'transport_inventory_tour_id' => 4,
                'cost' => '15.00',
                'created_at' => '2022-09-20 10:39:19',
                'updated_at' => '2022-09-20 10:39:19',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'order_customer_id' => 19,
                'transport_inventory_tour_id' => 5,
                'cost' => '10.00',
                'created_at' => '2022-09-20 19:33:03',
                'updated_at' => '2022-09-20 19:33:03',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'order_customer_id' => 19,
                'transport_inventory_tour_id' => 6,
                'cost' => '10.00',
                'created_at' => '2022-09-20 19:33:03',
                'updated_at' => '2022-09-20 19:33:03',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'order_customer_id' => 20,
                'transport_inventory_tour_id' => 5,
                'cost' => '10.00',
                'created_at' => '2022-09-20 19:33:03',
                'updated_at' => '2022-09-20 19:33:03',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'order_customer_id' => 20,
                'transport_inventory_tour_id' => 6,
                'cost' => '10.00',
                'created_at' => '2022-09-20 19:33:03',
                'updated_at' => '2022-09-20 19:33:03',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'order_customer_id' => 21,
                'transport_inventory_tour_id' => 7,
                'cost' => '30.00',
                'created_at' => '2022-09-21 09:24:11',
                'updated_at' => '2022-09-21 09:24:11',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'order_customer_id' => 21,
                'transport_inventory_tour_id' => 8,
                'cost' => '35.00',
                'created_at' => '2022-09-21 09:24:11',
                'updated_at' => '2022-09-21 09:24:11',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'order_customer_id' => 22,
                'transport_inventory_tour_id' => 7,
                'cost' => '30.00',
                'created_at' => '2022-09-21 09:27:54',
                'updated_at' => '2022-09-21 09:27:54',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'order_customer_id' => 22,
                'transport_inventory_tour_id' => 8,
                'cost' => '35.00',
                'created_at' => '2022-09-21 09:27:54',
                'updated_at' => '2022-09-21 09:27:54',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'order_customer_id' => 23,
                'transport_inventory_tour_id' => 7,
                'cost' => '30.00',
                'created_at' => '2022-09-21 09:30:34',
                'updated_at' => '2022-09-21 09:30:34',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'order_customer_id' => 23,
                'transport_inventory_tour_id' => 8,
                'cost' => '35.00',
                'created_at' => '2022-09-21 09:30:34',
                'updated_at' => '2022-09-21 09:30:34',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'order_customer_id' => 24,
                'transport_inventory_tour_id' => 7,
                'cost' => '30.00',
                'created_at' => '2022-09-21 09:31:56',
                'updated_at' => '2022-09-21 09:31:56',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'order_customer_id' => 24,
                'transport_inventory_tour_id' => 8,
                'cost' => '35.00',
                'created_at' => '2022-09-21 09:31:56',
                'updated_at' => '2022-09-21 09:31:56',
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'order_customer_id' => 25,
                'transport_inventory_tour_id' => 7,
                'cost' => '30.00',
                'created_at' => '2022-09-21 09:31:57',
                'updated_at' => '2022-09-21 09:31:57',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'order_customer_id' => 25,
                'transport_inventory_tour_id' => 8,
                'cost' => '35.00',
                'created_at' => '2022-09-21 09:31:57',
                'updated_at' => '2022-09-21 09:31:57',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'order_customer_id' => 26,
                'transport_inventory_tour_id' => 5,
                'cost' => '10.00',
                'created_at' => '2022-09-21 11:45:18',
                'updated_at' => '2022-09-21 11:45:18',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'order_customer_id' => 26,
                'transport_inventory_tour_id' => 6,
                'cost' => '10.00',
                'created_at' => '2022-09-21 11:45:18',
                'updated_at' => '2022-09-21 11:45:18',
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'order_customer_id' => 27,
                'transport_inventory_tour_id' => 5,
                'cost' => '10.00',
                'created_at' => '2022-09-21 12:44:00',
                'updated_at' => '2022-09-21 12:44:00',
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'order_customer_id' => 27,
                'transport_inventory_tour_id' => 6,
                'cost' => '10.00',
                'created_at' => '2022-09-21 12:44:00',
                'updated_at' => '2022-09-21 12:44:00',
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'order_customer_id' => 19,
                'transport_inventory_tour_id' => 5,
                'cost' => '10.00',
                'created_at' => '2022-09-21 13:55:02',
                'updated_at' => '2022-09-21 13:55:02',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}