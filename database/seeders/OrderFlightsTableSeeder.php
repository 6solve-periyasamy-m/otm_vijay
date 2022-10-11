<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderFlightsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_flights')->delete();
        
        \DB::table('order_flights')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'order_customer_id' => 2,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'order_customer_id' => 2,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'order_customer_id' => 2,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'order_customer_id' => 3,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'order_customer_id' => 3,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'order_customer_id' => 3,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'order_customer_id' => 4,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:38:49',
                'updated_at' => '2022-01-23 13:38:49',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'order_customer_id' => 4,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:38:49',
                'updated_at' => '2022-01-23 13:38:49',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'order_customer_id' => 4,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:38:49',
                'updated_at' => '2022-01-23 13:38:49',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'order_customer_id' => 5,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'order_customer_id' => 5,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'order_customer_id' => 5,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'order_customer_id' => 6,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'order_customer_id' => 6,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'order_customer_id' => 6,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'order_customer_id' => 7,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'order_customer_id' => 7,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'order_customer_id' => 7,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'order_customer_id' => 8,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:24',
                'updated_at' => '2022-06-29 07:59:24',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'order_customer_id' => 8,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:24',
                'updated_at' => '2022-06-29 07:59:24',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'order_customer_id' => 8,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:24',
                'updated_at' => '2022-06-29 07:59:24',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'order_customer_id' => 9,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'order_customer_id' => 9,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'order_customer_id' => 9,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'order_customer_id' => 10,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:05',
                'updated_at' => '2022-06-29 08:21:05',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'order_customer_id' => 10,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:05',
                'updated_at' => '2022-06-29 08:21:05',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'order_customer_id' => 10,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:05',
                'updated_at' => '2022-06-29 08:21:05',
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'order_customer_id' => 11,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'order_customer_id' => 11,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'order_customer_id' => 11,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'order_customer_id' => 12,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:08',
                'updated_at' => '2022-06-29 08:21:08',
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'order_customer_id' => 12,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:08',
                'updated_at' => '2022-06-29 08:21:08',
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'order_customer_id' => 12,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:09',
                'updated_at' => '2022-06-29 08:21:09',
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'order_customer_id' => 13,
                'flight_inventory_tour_id' => 1,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:10',
                'updated_at' => '2022-06-29 08:21:10',
                'deleted_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'order_customer_id' => 13,
                'flight_inventory_tour_id' => 2,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:11',
                'updated_at' => '2022-06-29 08:21:11',
                'deleted_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'order_customer_id' => 13,
                'flight_inventory_tour_id' => 7,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:11',
                'updated_at' => '2022-06-29 08:21:11',
                'deleted_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'order_customer_id' => 14,
                'flight_inventory_tour_id' => 16,
                'cost' => '850.00',
                'created_at' => '2022-09-14 11:22:23',
                'updated_at' => '2022-09-14 11:22:23',
                'deleted_at' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'order_customer_id' => 14,
                'flight_inventory_tour_id' => 15,
                'cost' => '974.00',
                'created_at' => '2022-09-14 11:22:23',
                'updated_at' => '2022-09-14 11:22:23',
                'deleted_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'order_customer_id' => 15,
                'flight_inventory_tour_id' => 16,
                'cost' => '850.00',
                'created_at' => '2022-09-14 11:22:24',
                'updated_at' => '2022-09-14 11:22:24',
                'deleted_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'order_customer_id' => 15,
                'flight_inventory_tour_id' => 15,
                'cost' => '974.00',
                'created_at' => '2022-09-14 11:22:24',
                'updated_at' => '2022-09-14 11:22:24',
                'deleted_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'order_customer_id' => 16,
                'flight_inventory_tour_id' => 17,
                'cost' => '974.00',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'order_customer_id' => 16,
                'flight_inventory_tour_id' => 18,
                'cost' => '850.00',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            45 => 
            array (
                'id' => 46,
                'order_customer_id' => 17,
                'flight_inventory_tour_id' => 17,
                'cost' => '974.00',
                'created_at' => '2022-09-20 10:39:18',
                'updated_at' => '2022-09-20 10:39:18',
                'deleted_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'order_customer_id' => 17,
                'flight_inventory_tour_id' => 18,
                'cost' => '850.00',
                'created_at' => '2022-09-20 10:39:18',
                'updated_at' => '2022-09-20 10:39:18',
                'deleted_at' => NULL,
            ),
            47 => 
            array (
                'id' => 48,
                'order_customer_id' => 18,
                'flight_inventory_tour_id' => 17,
                'cost' => '974.00',
                'created_at' => '2022-09-20 10:39:18',
                'updated_at' => '2022-09-20 10:39:18',
                'deleted_at' => NULL,
            ),
            48 => 
            array (
                'id' => 49,
                'order_customer_id' => 18,
                'flight_inventory_tour_id' => 18,
                'cost' => '850.00',
                'created_at' => '2022-09-20 10:39:19',
                'updated_at' => '2022-09-20 10:39:19',
                'deleted_at' => NULL,
            ),
            49 => 
            array (
                'id' => 50,
                'order_customer_id' => 19,
                'flight_inventory_tour_id' => 20,
                'cost' => '495.00',
                'created_at' => '2022-09-20 19:33:03',
                'updated_at' => '2022-09-20 19:33:03',
                'deleted_at' => NULL,
            ),
            50 => 
            array (
                'id' => 51,
                'order_customer_id' => 19,
                'flight_inventory_tour_id' => 19,
                'cost' => '400.00',
                'created_at' => '2022-09-20 19:33:03',
                'updated_at' => '2022-09-20 19:33:03',
                'deleted_at' => NULL,
            ),
            51 => 
            array (
                'id' => 52,
                'order_customer_id' => 20,
                'flight_inventory_tour_id' => 20,
                'cost' => '495.00',
                'created_at' => '2022-09-20 19:33:03',
                'updated_at' => '2022-09-20 19:33:03',
                'deleted_at' => NULL,
            ),
            52 => 
            array (
                'id' => 53,
                'order_customer_id' => 20,
                'flight_inventory_tour_id' => 19,
                'cost' => '400.00',
                'created_at' => '2022-09-20 19:33:03',
                'updated_at' => '2022-09-20 19:33:03',
                'deleted_at' => NULL,
            ),
            53 => 
            array (
                'id' => 54,
                'order_customer_id' => 26,
                'flight_inventory_tour_id' => 20,
                'cost' => '495.00',
                'created_at' => '2022-09-21 11:45:18',
                'updated_at' => '2022-09-21 11:45:18',
                'deleted_at' => NULL,
            ),
            54 => 
            array (
                'id' => 55,
                'order_customer_id' => 26,
                'flight_inventory_tour_id' => 19,
                'cost' => '400.00',
                'created_at' => '2022-09-21 11:45:18',
                'updated_at' => '2022-09-21 11:45:18',
                'deleted_at' => NULL,
            ),
            55 => 
            array (
                'id' => 56,
                'order_customer_id' => 27,
                'flight_inventory_tour_id' => 19,
                'cost' => '400.00',
                'created_at' => '2022-09-21 12:44:00',
                'updated_at' => '2022-09-21 12:44:00',
                'deleted_at' => NULL,
            ),
            56 => 
            array (
                'id' => 57,
                'order_customer_id' => 27,
                'flight_inventory_tour_id' => 20,
                'cost' => '495.00',
                'created_at' => '2022-09-21 12:44:00',
                'updated_at' => '2022-09-21 12:44:00',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}