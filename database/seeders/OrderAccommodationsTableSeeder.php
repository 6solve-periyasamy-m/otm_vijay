<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderAccommodationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_accommodations')->delete();
        
        \DB::table('order_accommodations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'group_id' => 1,
                'accommodation_inventory_tour_id' => 1,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:34',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => '2022-06-29 07:57:54',
            ),
            1 => 
            array (
                'id' => 2,
                'group_id' => 1,
                'accommodation_inventory_tour_id' => 2,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:34',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => '2022-06-29 07:57:54',
            ),
            2 => 
            array (
                'id' => 3,
                'group_id' => 1,
                'accommodation_inventory_tour_id' => 3,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:34',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => '2022-06-29 07:57:54',
            ),
            3 => 
            array (
                'id' => 4,
                'group_id' => 1,
                'accommodation_inventory_tour_id' => 4,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:34',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => '2022-06-29 07:57:54',
            ),
            4 => 
            array (
                'id' => 5,
                'group_id' => 1,
                'accommodation_inventory_tour_id' => 5,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:34',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => '2022-06-29 07:57:54',
            ),
            5 => 
            array (
                'id' => 6,
                'group_id' => 1,
                'accommodation_inventory_tour_id' => 6,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:34',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => '2022-06-29 07:57:54',
            ),
            6 => 
            array (
                'id' => 7,
                'group_id' => 1,
                'accommodation_inventory_tour_id' => 7,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:34',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => '2022-06-29 07:57:54',
            ),
            7 => 
            array (
                'id' => 8,
                'group_id' => 1,
                'accommodation_inventory_tour_id' => 8,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:34',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => '2022-06-29 07:57:54',
            ),
            8 => 
            array (
                'id' => 9,
                'group_id' => 1,
                'accommodation_inventory_tour_id' => 9,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:34',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => '2022-06-29 07:57:54',
            ),
            9 => 
            array (
                'id' => 10,
                'group_id' => 2,
                'accommodation_inventory_tour_id' => 1,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:54',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'group_id' => 2,
                'accommodation_inventory_tour_id' => 2,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:54',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'group_id' => 2,
                'accommodation_inventory_tour_id' => 3,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:54',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'group_id' => 2,
                'accommodation_inventory_tour_id' => 4,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:54',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'group_id' => 2,
                'accommodation_inventory_tour_id' => 5,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:54',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'group_id' => 2,
                'accommodation_inventory_tour_id' => 6,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:54',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'group_id' => 2,
                'accommodation_inventory_tour_id' => 7,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:54',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'group_id' => 2,
                'accommodation_inventory_tour_id' => 8,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:54',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'group_id' => 2,
                'accommodation_inventory_tour_id' => 9,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:57:54',
                'updated_at' => '2022-06-29 07:57:54',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'group_id' => 3,
                'accommodation_inventory_tour_id' => 1,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:58:32',
                'updated_at' => '2022-06-29 07:58:32',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'group_id' => 3,
                'accommodation_inventory_tour_id' => 2,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:58:32',
                'updated_at' => '2022-06-29 07:58:32',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'group_id' => 3,
                'accommodation_inventory_tour_id' => 3,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:58:32',
                'updated_at' => '2022-06-29 07:58:32',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'group_id' => 3,
                'accommodation_inventory_tour_id' => 4,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:58:32',
                'updated_at' => '2022-06-29 07:58:32',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'group_id' => 3,
                'accommodation_inventory_tour_id' => 5,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:58:32',
                'updated_at' => '2022-06-29 07:58:32',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'group_id' => 3,
                'accommodation_inventory_tour_id' => 6,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:58:32',
                'updated_at' => '2022-06-29 07:58:32',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'group_id' => 3,
                'accommodation_inventory_tour_id' => 7,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:58:32',
                'updated_at' => '2022-06-29 07:58:32',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'group_id' => 3,
                'accommodation_inventory_tour_id' => 8,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:58:32',
                'updated_at' => '2022-06-29 07:58:32',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'group_id' => 3,
                'accommodation_inventory_tour_id' => 9,
                'cost' => '45.00',
                'created_at' => '2022-06-29 07:58:32',
                'updated_at' => '2022-06-29 07:58:32',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'group_id' => 4,
                'accommodation_inventory_tour_id' => 17,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'group_id' => 4,
                'accommodation_inventory_tour_id' => 14,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'group_id' => 4,
                'accommodation_inventory_tour_id' => 11,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'group_id' => 4,
                'accommodation_inventory_tour_id' => 35,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'group_id' => 4,
                'accommodation_inventory_tour_id' => 32,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'group_id' => 4,
                'accommodation_inventory_tour_id' => 29,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'group_id' => 4,
                'accommodation_inventory_tour_id' => 26,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'group_id' => 4,
                'accommodation_inventory_tour_id' => 23,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'group_id' => 4,
                'accommodation_inventory_tour_id' => 20,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'group_id' => 5,
                'accommodation_inventory_tour_id' => 17,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'group_id' => 5,
                'accommodation_inventory_tour_id' => 14,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'group_id' => 5,
                'accommodation_inventory_tour_id' => 11,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'group_id' => 5,
                'accommodation_inventory_tour_id' => 35,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'group_id' => 5,
                'accommodation_inventory_tour_id' => 32,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'group_id' => 5,
                'accommodation_inventory_tour_id' => 29,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'group_id' => 5,
                'accommodation_inventory_tour_id' => 26,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'group_id' => 5,
                'accommodation_inventory_tour_id' => 23,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'group_id' => 5,
                'accommodation_inventory_tour_id' => 20,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:23',
                'updated_at' => '2022-06-29 07:59:23',
                'deleted_at' => NULL,
            ),
            45 => 
            array (
                'id' => 46,
                'group_id' => 6,
                'accommodation_inventory_tour_id' => 17,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:25',
                'updated_at' => '2022-06-29 07:59:25',
                'deleted_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'group_id' => 6,
                'accommodation_inventory_tour_id' => 14,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:25',
                'updated_at' => '2022-06-29 07:59:25',
                'deleted_at' => NULL,
            ),
            47 => 
            array (
                'id' => 48,
                'group_id' => 6,
                'accommodation_inventory_tour_id' => 11,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:25',
                'updated_at' => '2022-06-29 07:59:25',
                'deleted_at' => NULL,
            ),
            48 => 
            array (
                'id' => 49,
                'group_id' => 6,
                'accommodation_inventory_tour_id' => 35,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:25',
                'updated_at' => '2022-06-29 07:59:25',
                'deleted_at' => NULL,
            ),
            49 => 
            array (
                'id' => 50,
                'group_id' => 6,
                'accommodation_inventory_tour_id' => 32,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:25',
                'updated_at' => '2022-06-29 07:59:25',
                'deleted_at' => NULL,
            ),
            50 => 
            array (
                'id' => 51,
                'group_id' => 6,
                'accommodation_inventory_tour_id' => 29,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:25',
                'updated_at' => '2022-06-29 07:59:25',
                'deleted_at' => NULL,
            ),
            51 => 
            array (
                'id' => 52,
                'group_id' => 6,
                'accommodation_inventory_tour_id' => 26,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:25',
                'updated_at' => '2022-06-29 07:59:25',
                'deleted_at' => NULL,
            ),
            52 => 
            array (
                'id' => 53,
                'group_id' => 6,
                'accommodation_inventory_tour_id' => 23,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:25',
                'updated_at' => '2022-06-29 07:59:25',
                'deleted_at' => NULL,
            ),
            53 => 
            array (
                'id' => 54,
                'group_id' => 6,
                'accommodation_inventory_tour_id' => 20,
                'cost' => '50.00',
                'created_at' => '2022-06-29 07:59:25',
                'updated_at' => '2022-06-29 07:59:25',
                'deleted_at' => NULL,
            ),
            54 => 
            array (
                'id' => 55,
                'group_id' => 7,
                'accommodation_inventory_tour_id' => 17,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            55 => 
            array (
                'id' => 56,
                'group_id' => 7,
                'accommodation_inventory_tour_id' => 14,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            56 => 
            array (
                'id' => 57,
                'group_id' => 7,
                'accommodation_inventory_tour_id' => 11,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            57 => 
            array (
                'id' => 58,
                'group_id' => 7,
                'accommodation_inventory_tour_id' => 35,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            58 => 
            array (
                'id' => 59,
                'group_id' => 7,
                'accommodation_inventory_tour_id' => 32,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            59 => 
            array (
                'id' => 60,
                'group_id' => 7,
                'accommodation_inventory_tour_id' => 29,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            60 => 
            array (
                'id' => 61,
                'group_id' => 7,
                'accommodation_inventory_tour_id' => 26,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            61 => 
            array (
                'id' => 62,
                'group_id' => 7,
                'accommodation_inventory_tour_id' => 23,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            62 => 
            array (
                'id' => 63,
                'group_id' => 7,
                'accommodation_inventory_tour_id' => 20,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            63 => 
            array (
                'id' => 64,
                'group_id' => 8,
                'accommodation_inventory_tour_id' => 17,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            64 => 
            array (
                'id' => 65,
                'group_id' => 8,
                'accommodation_inventory_tour_id' => 14,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            65 => 
            array (
                'id' => 66,
                'group_id' => 8,
                'accommodation_inventory_tour_id' => 11,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            66 => 
            array (
                'id' => 67,
                'group_id' => 8,
                'accommodation_inventory_tour_id' => 35,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            67 => 
            array (
                'id' => 68,
                'group_id' => 8,
                'accommodation_inventory_tour_id' => 32,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            68 => 
            array (
                'id' => 69,
                'group_id' => 8,
                'accommodation_inventory_tour_id' => 29,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            69 => 
            array (
                'id' => 70,
                'group_id' => 8,
                'accommodation_inventory_tour_id' => 26,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            70 => 
            array (
                'id' => 71,
                'group_id' => 8,
                'accommodation_inventory_tour_id' => 23,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            71 => 
            array (
                'id' => 72,
                'group_id' => 8,
                'accommodation_inventory_tour_id' => 20,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:06',
                'updated_at' => '2022-06-29 08:21:06',
                'deleted_at' => NULL,
            ),
            72 => 
            array (
                'id' => 73,
                'group_id' => 9,
                'accommodation_inventory_tour_id' => 17,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            73 => 
            array (
                'id' => 74,
                'group_id' => 9,
                'accommodation_inventory_tour_id' => 14,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            74 => 
            array (
                'id' => 75,
                'group_id' => 9,
                'accommodation_inventory_tour_id' => 11,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            75 => 
            array (
                'id' => 76,
                'group_id' => 9,
                'accommodation_inventory_tour_id' => 35,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            76 => 
            array (
                'id' => 77,
                'group_id' => 9,
                'accommodation_inventory_tour_id' => 32,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            77 => 
            array (
                'id' => 78,
                'group_id' => 9,
                'accommodation_inventory_tour_id' => 29,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            78 => 
            array (
                'id' => 79,
                'group_id' => 9,
                'accommodation_inventory_tour_id' => 26,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            79 => 
            array (
                'id' => 80,
                'group_id' => 9,
                'accommodation_inventory_tour_id' => 23,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            80 => 
            array (
                'id' => 81,
                'group_id' => 9,
                'accommodation_inventory_tour_id' => 20,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:07',
                'updated_at' => '2022-06-29 08:21:07',
                'deleted_at' => NULL,
            ),
            81 => 
            array (
                'id' => 82,
                'group_id' => 10,
                'accommodation_inventory_tour_id' => 17,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:09',
                'updated_at' => '2022-06-29 08:21:09',
                'deleted_at' => NULL,
            ),
            82 => 
            array (
                'id' => 83,
                'group_id' => 10,
                'accommodation_inventory_tour_id' => 14,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:09',
                'updated_at' => '2022-06-29 08:21:09',
                'deleted_at' => NULL,
            ),
            83 => 
            array (
                'id' => 84,
                'group_id' => 10,
                'accommodation_inventory_tour_id' => 11,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:09',
                'updated_at' => '2022-06-29 08:21:09',
                'deleted_at' => NULL,
            ),
            84 => 
            array (
                'id' => 85,
                'group_id' => 10,
                'accommodation_inventory_tour_id' => 35,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:09',
                'updated_at' => '2022-06-29 08:21:09',
                'deleted_at' => NULL,
            ),
            85 => 
            array (
                'id' => 86,
                'group_id' => 10,
                'accommodation_inventory_tour_id' => 32,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:09',
                'updated_at' => '2022-06-29 08:21:09',
                'deleted_at' => NULL,
            ),
            86 => 
            array (
                'id' => 87,
                'group_id' => 10,
                'accommodation_inventory_tour_id' => 29,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:09',
                'updated_at' => '2022-06-29 08:21:09',
                'deleted_at' => NULL,
            ),
            87 => 
            array (
                'id' => 88,
                'group_id' => 10,
                'accommodation_inventory_tour_id' => 26,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:09',
                'updated_at' => '2022-06-29 08:21:09',
                'deleted_at' => NULL,
            ),
            88 => 
            array (
                'id' => 89,
                'group_id' => 10,
                'accommodation_inventory_tour_id' => 23,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:09',
                'updated_at' => '2022-06-29 08:21:09',
                'deleted_at' => NULL,
            ),
            89 => 
            array (
                'id' => 90,
                'group_id' => 10,
                'accommodation_inventory_tour_id' => 20,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:09',
                'updated_at' => '2022-06-29 08:21:09',
                'deleted_at' => NULL,
            ),
            90 => 
            array (
                'id' => 91,
                'group_id' => 11,
                'accommodation_inventory_tour_id' => 17,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:12',
                'updated_at' => '2022-06-29 08:21:12',
                'deleted_at' => NULL,
            ),
            91 => 
            array (
                'id' => 92,
                'group_id' => 11,
                'accommodation_inventory_tour_id' => 14,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:12',
                'updated_at' => '2022-06-29 08:21:12',
                'deleted_at' => NULL,
            ),
            92 => 
            array (
                'id' => 93,
                'group_id' => 11,
                'accommodation_inventory_tour_id' => 11,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:12',
                'updated_at' => '2022-06-29 08:21:12',
                'deleted_at' => NULL,
            ),
            93 => 
            array (
                'id' => 94,
                'group_id' => 11,
                'accommodation_inventory_tour_id' => 35,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:12',
                'updated_at' => '2022-06-29 08:21:12',
                'deleted_at' => NULL,
            ),
            94 => 
            array (
                'id' => 95,
                'group_id' => 11,
                'accommodation_inventory_tour_id' => 32,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:12',
                'updated_at' => '2022-06-29 08:21:12',
                'deleted_at' => NULL,
            ),
            95 => 
            array (
                'id' => 96,
                'group_id' => 11,
                'accommodation_inventory_tour_id' => 29,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:12',
                'updated_at' => '2022-06-29 08:21:12',
                'deleted_at' => NULL,
            ),
            96 => 
            array (
                'id' => 97,
                'group_id' => 11,
                'accommodation_inventory_tour_id' => 26,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:12',
                'updated_at' => '2022-06-29 08:21:12',
                'deleted_at' => NULL,
            ),
            97 => 
            array (
                'id' => 98,
                'group_id' => 11,
                'accommodation_inventory_tour_id' => 23,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:12',
                'updated_at' => '2022-06-29 08:21:12',
                'deleted_at' => NULL,
            ),
            98 => 
            array (
                'id' => 99,
                'group_id' => 11,
                'accommodation_inventory_tour_id' => 20,
                'cost' => '50.00',
                'created_at' => '2022-06-29 08:21:12',
                'updated_at' => '2022-06-29 08:21:12',
                'deleted_at' => NULL,
            ),
            99 => 
            array (
                'id' => 100,
                'group_id' => 12,
                'accommodation_inventory_tour_id' => 17,
                'cost' => '50.00',
                'created_at' => '2022-07-19 08:37:50',
                'updated_at' => '2022-07-19 08:37:50',
                'deleted_at' => NULL,
            ),
            100 => 
            array (
                'id' => 101,
                'group_id' => 12,
                'accommodation_inventory_tour_id' => 14,
                'cost' => '50.00',
                'created_at' => '2022-07-19 08:37:50',
                'updated_at' => '2022-07-19 08:37:50',
                'deleted_at' => NULL,
            ),
            101 => 
            array (
                'id' => 102,
                'group_id' => 12,
                'accommodation_inventory_tour_id' => 11,
                'cost' => '50.00',
                'created_at' => '2022-07-19 08:37:50',
                'updated_at' => '2022-07-19 08:37:50',
                'deleted_at' => NULL,
            ),
            102 => 
            array (
                'id' => 103,
                'group_id' => 12,
                'accommodation_inventory_tour_id' => 35,
                'cost' => '50.00',
                'created_at' => '2022-07-19 08:37:50',
                'updated_at' => '2022-07-19 08:37:50',
                'deleted_at' => NULL,
            ),
            103 => 
            array (
                'id' => 104,
                'group_id' => 12,
                'accommodation_inventory_tour_id' => 32,
                'cost' => '50.00',
                'created_at' => '2022-07-19 08:37:50',
                'updated_at' => '2022-07-19 08:37:50',
                'deleted_at' => NULL,
            ),
            104 => 
            array (
                'id' => 105,
                'group_id' => 12,
                'accommodation_inventory_tour_id' => 29,
                'cost' => '50.00',
                'created_at' => '2022-07-19 08:37:50',
                'updated_at' => '2022-07-19 08:37:50',
                'deleted_at' => NULL,
            ),
            105 => 
            array (
                'id' => 106,
                'group_id' => 12,
                'accommodation_inventory_tour_id' => 26,
                'cost' => '50.00',
                'created_at' => '2022-07-19 08:37:50',
                'updated_at' => '2022-07-19 08:37:50',
                'deleted_at' => NULL,
            ),
            106 => 
            array (
                'id' => 107,
                'group_id' => 12,
                'accommodation_inventory_tour_id' => 23,
                'cost' => '50.00',
                'created_at' => '2022-07-19 08:37:50',
                'updated_at' => '2022-07-19 08:37:50',
                'deleted_at' => NULL,
            ),
            107 => 
            array (
                'id' => 108,
                'group_id' => 12,
                'accommodation_inventory_tour_id' => 20,
                'cost' => '50.00',
                'created_at' => '2022-07-19 08:37:50',
                'updated_at' => '2022-07-19 08:37:50',
                'deleted_at' => NULL,
            ),
            108 => 
            array (
                'id' => 109,
                'group_id' => 13,
                'accommodation_inventory_tour_id' => 100,
                'cost' => '45.00',
                'created_at' => '2022-09-14 11:22:24',
                'updated_at' => '2022-09-20 10:22:55',
                'deleted_at' => '2022-09-20 10:22:55',
            ),
            109 => 
            array (
                'id' => 110,
                'group_id' => 13,
                'accommodation_inventory_tour_id' => 102,
                'cost' => '80.00',
                'created_at' => '2022-09-14 11:22:24',
                'updated_at' => '2022-09-20 10:22:55',
                'deleted_at' => '2022-09-20 10:22:55',
            ),
            110 => 
            array (
                'id' => 111,
                'group_id' => 14,
                'accommodation_inventory_tour_id' => 104,
                'cost' => '30.00',
                'created_at' => '2022-09-20 10:22:55',
                'updated_at' => '2022-09-20 10:25:19',
                'deleted_at' => NULL,
            ),
            111 => 
            array (
                'id' => 112,
                'group_id' => 14,
                'accommodation_inventory_tour_id' => 102,
                'cost' => '80.00',
                'created_at' => '2022-09-20 10:22:55',
                'updated_at' => '2022-09-20 10:22:55',
                'deleted_at' => NULL,
            ),
            112 => 
            array (
                'id' => 113,
                'group_id' => 15,
                'accommodation_inventory_tour_id' => 100,
                'cost' => '45.00',
                'created_at' => '2022-09-20 10:22:55',
                'updated_at' => '2022-09-20 10:22:55',
                'deleted_at' => NULL,
            ),
            113 => 
            array (
                'id' => 114,
                'group_id' => 15,
                'accommodation_inventory_tour_id' => 102,
                'cost' => '80.00',
                'created_at' => '2022-09-20 10:22:55',
                'updated_at' => '2022-09-20 10:22:55',
                'deleted_at' => NULL,
            ),
            114 => 
            array (
                'id' => 115,
                'group_id' => 16,
                'accommodation_inventory_tour_id' => 108,
                'cost' => '40.00',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            115 => 
            array (
                'id' => 116,
                'group_id' => 16,
                'accommodation_inventory_tour_id' => 110,
                'cost' => '80.00',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            116 => 
            array (
                'id' => 117,
                'group_id' => 17,
                'accommodation_inventory_tour_id' => 108,
                'cost' => '40.00',
                'created_at' => '2022-09-20 10:39:18',
                'updated_at' => '2022-09-20 10:39:18',
                'deleted_at' => NULL,
            ),
            117 => 
            array (
                'id' => 118,
                'group_id' => 17,
                'accommodation_inventory_tour_id' => 110,
                'cost' => '80.00',
                'created_at' => '2022-09-20 10:39:18',
                'updated_at' => '2022-09-20 10:39:18',
                'deleted_at' => NULL,
            ),
            118 => 
            array (
                'id' => 119,
                'group_id' => 18,
                'accommodation_inventory_tour_id' => 108,
                'cost' => '40.00',
                'created_at' => '2022-09-20 10:39:19',
                'updated_at' => '2022-09-20 10:39:19',
                'deleted_at' => NULL,
            ),
            119 => 
            array (
                'id' => 120,
                'group_id' => 18,
                'accommodation_inventory_tour_id' => 110,
                'cost' => '80.00',
                'created_at' => '2022-09-20 10:39:19',
                'updated_at' => '2022-09-20 10:39:19',
                'deleted_at' => NULL,
            ),
            120 => 
            array (
                'id' => 121,
                'group_id' => 19,
                'accommodation_inventory_tour_id' => 112,
                'cost' => '150.00',
                'created_at' => '2022-09-20 19:33:03',
                'updated_at' => '2022-09-21 12:09:54',
                'deleted_at' => '2022-09-21 12:09:54',
            ),
            121 => 
            array (
                'id' => 122,
                'group_id' => 20,
                'accommodation_inventory_tour_id' => 112,
                'cost' => '150.00',
                'created_at' => '2022-09-21 11:45:18',
                'updated_at' => '2022-09-21 11:45:18',
                'deleted_at' => NULL,
            ),
            122 => 
            array (
                'id' => 123,
                'group_id' => 21,
                'accommodation_inventory_tour_id' => 112,
                'cost' => '150.00',
                'created_at' => '2022-09-21 12:09:54',
                'updated_at' => '2022-09-21 12:11:35',
                'deleted_at' => '2022-09-21 12:11:35',
            ),
            123 => 
            array (
                'id' => 124,
                'group_id' => 22,
                'accommodation_inventory_tour_id' => 112,
                'cost' => '150.00',
                'created_at' => '2022-09-21 12:09:54',
                'updated_at' => '2022-09-21 12:11:35',
                'deleted_at' => '2022-09-21 12:11:35',
            ),
            124 => 
            array (
                'id' => 125,
                'group_id' => 23,
                'accommodation_inventory_tour_id' => 112,
                'cost' => '150.00',
                'created_at' => '2022-09-21 12:11:35',
                'updated_at' => '2022-09-21 12:11:35',
                'deleted_at' => NULL,
            ),
            125 => 
            array (
                'id' => 126,
                'group_id' => 24,
                'accommodation_inventory_tour_id' => 112,
                'cost' => '150.00',
                'created_at' => '2022-09-21 12:44:00',
                'updated_at' => '2022-09-21 12:44:00',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}