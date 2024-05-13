<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderActivitiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_activities')->delete();
        
        \DB::table('order_activities')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_customer_id' => 1,
                'activity_inventory_tour_id' => 1,
                'cost' => '25.00',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'order_customer_id' => 1,
                'activity_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'order_customer_id' => 1,
                'activity_inventory_tour_id' => 3,
                'cost' => '250.00',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'order_customer_id' => 1,
                'activity_inventory_tour_id' => 4,
                'cost' => '40.00',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'order_customer_id' => 2,
                'activity_inventory_tour_id' => 1,
                'cost' => '25.00',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'order_customer_id' => 2,
                'activity_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'order_customer_id' => 2,
                'activity_inventory_tour_id' => 3,
                'cost' => '250.00',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'order_customer_id' => 2,
                'activity_inventory_tour_id' => 4,
                'cost' => '40.00',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'order_customer_id' => 3,
                'activity_inventory_tour_id' => 1,
                'cost' => '25.00',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'order_customer_id' => 3,
                'activity_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'order_customer_id' => 3,
                'activity_inventory_tour_id' => 3,
                'cost' => '250.00',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'order_customer_id' => 3,
                'activity_inventory_tour_id' => 4,
                'cost' => '40.00',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'order_customer_id' => 4,
                'activity_inventory_tour_id' => 1,
                'cost' => '25.00',
                'created_at' => '2022-01-23 13:38:49',
                'updated_at' => '2022-01-23 13:38:49',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'order_customer_id' => 4,
                'activity_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:38:49',
                'updated_at' => '2022-01-23 13:38:49',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'order_customer_id' => 4,
                'activity_inventory_tour_id' => 3,
                'cost' => '250.00',
                'created_at' => '2022-01-23 13:38:49',
                'updated_at' => '2022-01-23 13:38:49',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'order_customer_id' => 4,
                'activity_inventory_tour_id' => 4,
                'cost' => '40.00',
                'created_at' => '2022-01-23 13:38:49',
                'updated_at' => '2022-01-23 13:38:49',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'order_customer_id' => 5,
                'activity_inventory_tour_id' => 1,
                'cost' => '25.00',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'order_customer_id' => 5,
                'activity_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'order_customer_id' => 5,
                'activity_inventory_tour_id' => 3,
                'cost' => '250.00',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'order_customer_id' => 5,
                'activity_inventory_tour_id' => 4,
                'cost' => '40.00',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'order_customer_id' => 6,
                'activity_inventory_tour_id' => 1,
                'cost' => '25.00',
                'created_at' => '2022-06-29 07:59:19',
                'updated_at' => '2022-06-29 07:59:19',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'order_customer_id' => 6,
                'activity_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:19',
                'updated_at' => '2022-06-29 07:59:19',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'order_customer_id' => 6,
                'activity_inventory_tour_id' => 3,
                'cost' => '250.00',
                'created_at' => '2022-06-29 07:59:19',
                'updated_at' => '2022-06-29 07:59:19',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'order_customer_id' => 6,
                'activity_inventory_tour_id' => 4,
                'cost' => '40.00',
                'created_at' => '2022-06-29 07:59:19',
                'updated_at' => '2022-06-29 07:59:19',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'order_customer_id' => 7,
                'activity_inventory_tour_id' => 1,
                'cost' => '25.00',
                'created_at' => '2022-06-29 07:59:22',
                'updated_at' => '2022-06-29 07:59:22',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'order_customer_id' => 7,
                'activity_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:22',
                'updated_at' => '2022-06-29 07:59:22',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'order_customer_id' => 7,
                'activity_inventory_tour_id' => 3,
                'cost' => '250.00',
                'created_at' => '2022-06-29 07:59:22',
                'updated_at' => '2022-06-29 07:59:22',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'order_customer_id' => 7,
                'activity_inventory_tour_id' => 4,
                'cost' => '40.00',
                'created_at' => '2022-06-29 07:59:22',
                'updated_at' => '2022-06-29 07:59:22',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'order_customer_id' => 8,
                'activity_inventory_tour_id' => 1,
                'cost' => '25.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'order_customer_id' => 8,
                'activity_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'order_customer_id' => 8,
                'activity_inventory_tour_id' => 3,
                'cost' => '250.00',
                'created_at' => '2022-06-29 07:59:24',
                'updated_at' => '2022-06-29 07:59:24',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'order_customer_id' => 8,
                'activity_inventory_tour_id' => 4,
                'cost' => '40.00',
                'created_at' => '2022-06-29 07:59:24',
                'updated_at' => '2022-06-29 07:59:24',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'order_customer_id' => 9,
                'activity_inventory_tour_id' => 1,
                'cost' => '25.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'order_customer_id' => 9,
                'activity_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'order_customer_id' => 9,
                'activity_inventory_tour_id' => 3,
                'cost' => '250.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'order_customer_id' => 9,
                'activity_inventory_tour_id' => 4,
                'cost' => '40.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'order_customer_id' => 10,
                'activity_inventory_tour_id' => 1,
                'cost' => '25.00',
                'created_at' => '2022-06-29 08:21:04',
                'updated_at' => '2022-06-29 08:21:04',
                'deleted_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'order_customer_id' => 10,
                'activity_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:05',
                'updated_at' => '2022-06-29 08:21:05',
                'deleted_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'order_customer_id' => 10,
                'activity_inventory_tour_id' => 3,
                'cost' => '250.00',
                'created_at' => '2022-06-29 08:21:05',
                'updated_at' => '2022-06-29 08:21:05',
                'deleted_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'order_customer_id' => 10,
                'activity_inventory_tour_id' => 4,
                'cost' => '40.00',
                'created_at' => '2022-06-29 08:21:05',
                'updated_at' => '2022-06-29 08:21:05',
                'deleted_at' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'order_customer_id' => 11,
                'activity_inventory_tour_id' => 1,
                'cost' => '25.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'order_customer_id' => 11,
                'activity_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'order_customer_id' => 11,
                'activity_inventory_tour_id' => 3,
                'cost' => '250.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'order_customer_id' => 11,
                'activity_inventory_tour_id' => 4,
                'cost' => '40.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'order_customer_id' => 12,
                'activity_inventory_tour_id' => 1,
                'cost' => '25.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            45 => 
            array (
                'id' => 46,
                'order_customer_id' => 12,
                'activity_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'order_customer_id' => 12,
                'activity_inventory_tour_id' => 3,
                'cost' => '250.00',
                'created_at' => '2022-06-29 08:21:08',
                'updated_at' => '2022-06-29 08:21:08',
                'deleted_at' => NULL,
            ),
            47 => 
            array (
                'id' => 48,
                'order_customer_id' => 12,
                'activity_inventory_tour_id' => 4,
                'cost' => '40.00',
                'created_at' => '2022-06-29 08:21:08',
                'updated_at' => '2022-06-29 08:21:08',
                'deleted_at' => NULL,
            ),
            48 => 
            array (
                'id' => 49,
                'order_customer_id' => 13,
                'activity_inventory_tour_id' => 1,
                'cost' => '25.00',
                'created_at' => '2022-06-29 08:21:09',
                'updated_at' => '2022-06-29 08:21:09',
                'deleted_at' => NULL,
            ),
            49 => 
            array (
                'id' => 50,
                'order_customer_id' => 13,
                'activity_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:09',
                'updated_at' => '2022-06-29 08:21:09',
                'deleted_at' => NULL,
            ),
            50 => 
            array (
                'id' => 51,
                'order_customer_id' => 13,
                'activity_inventory_tour_id' => 3,
                'cost' => '250.00',
                'created_at' => '2022-06-29 08:21:10',
                'updated_at' => '2022-06-29 08:21:10',
                'deleted_at' => NULL,
            ),
            51 => 
            array (
                'id' => 52,
                'order_customer_id' => 13,
                'activity_inventory_tour_id' => 4,
                'cost' => '40.00',
                'created_at' => '2022-06-29 08:21:10',
                'updated_at' => '2022-06-29 08:21:10',
                'deleted_at' => NULL,
            ),
            52 => 
            array (
                'id' => 53,
                'order_customer_id' => 14,
                'activity_inventory_tour_id' => 17,
                'cost' => '350.00',
                'created_at' => '2022-09-14 11:22:23',
                'updated_at' => '2022-09-14 11:22:23',
                'deleted_at' => NULL,
            ),
            53 => 
            array (
                'id' => 54,
                'order_customer_id' => 14,
                'activity_inventory_tour_id' => 18,
                'cost' => '360.00',
                'created_at' => '2022-09-14 11:22:23',
                'updated_at' => '2022-09-14 11:22:23',
                'deleted_at' => NULL,
            ),
            54 => 
            array (
                'id' => 55,
                'order_customer_id' => 15,
                'activity_inventory_tour_id' => 17,
                'cost' => '350.00',
                'created_at' => '2022-09-14 11:22:24',
                'updated_at' => '2022-09-14 11:22:24',
                'deleted_at' => NULL,
            ),
            55 => 
            array (
                'id' => 56,
                'order_customer_id' => 15,
                'activity_inventory_tour_id' => 18,
                'cost' => '360.00',
                'created_at' => '2022-09-14 11:22:24',
                'updated_at' => '2022-09-14 11:22:24',
                'deleted_at' => NULL,
            ),
            56 => 
            array (
                'id' => 57,
                'order_customer_id' => 16,
                'activity_inventory_tour_id' => 20,
                'cost' => '350.00',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            57 => 
            array (
                'id' => 58,
                'order_customer_id' => 16,
                'activity_inventory_tour_id' => 21,
                'cost' => '360.00',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            58 => 
            array (
                'id' => 59,
                'order_customer_id' => 17,
                'activity_inventory_tour_id' => 20,
                'cost' => '350.00',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            59 => 
            array (
                'id' => 60,
                'order_customer_id' => 17,
                'activity_inventory_tour_id' => 21,
                'cost' => '360.00',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            60 => 
            array (
                'id' => 61,
                'order_customer_id' => 18,
                'activity_inventory_tour_id' => 20,
                'cost' => '350.00',
                'created_at' => '2022-09-20 10:39:18',
                'updated_at' => '2022-09-20 10:39:18',
                'deleted_at' => NULL,
            ),
            61 => 
            array (
                'id' => 62,
                'order_customer_id' => 18,
                'activity_inventory_tour_id' => 21,
                'cost' => '360.00',
                'created_at' => '2022-09-20 10:39:18',
                'updated_at' => '2022-09-20 10:39:18',
                'deleted_at' => NULL,
            ),
            62 => 
            array (
                'id' => 63,
                'order_customer_id' => 21,
                'activity_inventory_tour_id' => 23,
                'cost' => '75.00',
                'created_at' => '2022-09-21 09:24:11',
                'updated_at' => '2022-09-21 09:24:11',
                'deleted_at' => NULL,
            ),
            63 => 
            array (
                'id' => 64,
                'order_customer_id' => 22,
                'activity_inventory_tour_id' => 23,
                'cost' => '75.00',
                'created_at' => '2022-09-21 09:27:53',
                'updated_at' => '2022-09-21 09:27:53',
                'deleted_at' => NULL,
            ),
            64 => 
            array (
                'id' => 65,
                'order_customer_id' => 23,
                'activity_inventory_tour_id' => 23,
                'cost' => '75.00',
                'created_at' => '2022-09-21 09:30:33',
                'updated_at' => '2022-09-21 09:30:33',
                'deleted_at' => NULL,
            ),
            65 => 
            array (
                'id' => 66,
                'order_customer_id' => 24,
                'activity_inventory_tour_id' => 23,
                'cost' => '75.00',
                'created_at' => '2022-09-21 09:31:56',
                'updated_at' => '2022-09-21 09:31:56',
                'deleted_at' => NULL,
            ),
            66 => 
            array (
                'id' => 67,
                'order_customer_id' => 25,
                'activity_inventory_tour_id' => 23,
                'cost' => '75.00',
                'created_at' => '2022-09-21 09:31:57',
                'updated_at' => '2022-09-21 09:31:57',
                'deleted_at' => NULL,
            ),
            67 => 
            array (
                'id' => 68,
                'order_customer_id' => 26,
                'activity_inventory_tour_id' => 22,
                'cost' => '150.00',
                'created_at' => '2022-09-21 11:45:17',
                'updated_at' => '2022-09-21 11:45:17',
                'deleted_at' => NULL,
            ),
            68 => 
            array (
                'id' => 69,
                'order_customer_id' => 27,
                'activity_inventory_tour_id' => 22,
                'cost' => '150.00',
                'created_at' => '2022-09-21 12:44:33',
                'updated_at' => '2022-09-21 12:44:33',
                'deleted_at' => NULL,
            ),
            69 => 
            array (
                'id' => 70,
                'order_customer_id' => 28,
                'activity_inventory_tour_id' => 24,
                'cost' => '25.00',
                'created_at' => '2023-09-22 13:20:35',
                'updated_at' => '2023-09-22 13:20:35',
                'deleted_at' => NULL,
            ),
            70 => 
            array (
                'id' => 71,
                'order_customer_id' => 28,
                'activity_inventory_tour_id' => 25,
                'cost' => '30.00',
                'created_at' => '2023-09-22 13:20:35',
                'updated_at' => '2023-09-22 13:20:35',
                'deleted_at' => NULL,
            ),
            71 => 
            array (
                'id' => 72,
                'order_customer_id' => 29,
                'activity_inventory_tour_id' => 24,
                'cost' => '25.00',
                'created_at' => '2023-09-22 13:20:35',
                'updated_at' => '2023-09-22 13:20:35',
                'deleted_at' => NULL,
            ),
            72 => 
            array (
                'id' => 73,
                'order_customer_id' => 29,
                'activity_inventory_tour_id' => 25,
                'cost' => '30.00',
                'created_at' => '2023-09-22 13:20:35',
                'updated_at' => '2023-09-22 13:20:35',
                'deleted_at' => NULL,
            ),
            73 => 
            array (
                'id' => 74,
                'order_customer_id' => 30,
                'activity_inventory_tour_id' => 24,
                'cost' => '25.00',
                'created_at' => '2023-09-22 13:20:35',
                'updated_at' => '2023-09-22 13:20:35',
                'deleted_at' => NULL,
            ),
            74 => 
            array (
                'id' => 75,
                'order_customer_id' => 30,
                'activity_inventory_tour_id' => 25,
                'cost' => '30.00',
                'created_at' => '2023-09-22 13:20:35',
                'updated_at' => '2023-09-22 13:20:35',
                'deleted_at' => NULL,
            ),
            75 => 
            array (
                'id' => 76,
                'order_customer_id' => 31,
                'activity_inventory_tour_id' => 24,
                'cost' => '25.00',
                'created_at' => '2023-09-22 13:20:35',
                'updated_at' => '2023-09-22 13:20:35',
                'deleted_at' => NULL,
            ),
            76 => 
            array (
                'id' => 77,
                'order_customer_id' => 31,
                'activity_inventory_tour_id' => 25,
                'cost' => '30.00',
                'created_at' => '2023-09-22 13:20:35',
                'updated_at' => '2023-09-22 13:20:35',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}