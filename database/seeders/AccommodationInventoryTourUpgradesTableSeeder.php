<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccommodationInventoryTourUpgradesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('accommodation_inventory_tour_upgrades')->delete();
        
        \DB::table('accommodation_inventory_tour_upgrades')->insert(array (
            0 => 
            array (
                'id' => 1,
                'base_id' => 3,
                'upgrade_id' => 10,
                'description' => 'Breakfast Included',
                'created_at' => '2022-01-20 12:56:34',
                'updated_at' => '2022-01-20 12:56:34',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'base_id' => 3,
                'upgrade_id' => 11,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-01-20 13:08:43',
                'updated_at' => '2022-01-20 13:08:43',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'base_id' => 3,
                'upgrade_id' => 12,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-01-20 13:09:17',
                'updated_at' => '2022-01-20 13:09:17',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'base_id' => 2,
                'upgrade_id' => 13,
                'description' => 'Breakfast Included',
                'created_at' => '2022-01-20 13:12:50',
                'updated_at' => '2022-01-20 13:12:50',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'base_id' => 2,
                'upgrade_id' => 14,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-01-20 13:13:03',
                'updated_at' => '2022-01-20 13:13:03',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'base_id' => 2,
                'upgrade_id' => 15,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-01-20 13:13:16',
                'updated_at' => '2022-01-20 13:13:16',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'base_id' => 1,
                'upgrade_id' => 16,
                'description' => 'Breakfast Included',
                'created_at' => '2022-01-20 13:13:55',
                'updated_at' => '2022-01-20 13:13:55',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'base_id' => 1,
                'upgrade_id' => 17,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-01-20 13:14:07',
                'updated_at' => '2022-01-20 13:14:07',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'base_id' => 1,
                'upgrade_id' => 18,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-01-20 13:14:21',
                'updated_at' => '2022-01-20 13:14:21',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'base_id' => 9,
                'upgrade_id' => 19,
                'description' => 'Breakfast Included',
                'created_at' => '2022-01-20 13:14:57',
                'updated_at' => '2022-01-20 13:14:57',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'base_id' => 9,
                'upgrade_id' => 20,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-01-20 13:15:19',
                'updated_at' => '2022-01-20 13:15:19',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'base_id' => 9,
                'upgrade_id' => 21,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-01-20 13:15:28',
                'updated_at' => '2022-01-20 13:15:28',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'base_id' => 8,
                'upgrade_id' => 22,
                'description' => 'Breakfast Included',
                'created_at' => '2022-01-20 13:16:30',
                'updated_at' => '2022-01-20 13:16:30',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'base_id' => 8,
                'upgrade_id' => 23,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-01-20 13:16:44',
                'updated_at' => '2022-01-20 13:16:44',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'base_id' => 8,
                'upgrade_id' => 24,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-01-20 13:17:51',
                'updated_at' => '2022-01-20 13:17:51',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'base_id' => 7,
                'upgrade_id' => 25,
                'description' => 'Breakfast Included',
                'created_at' => '2022-01-20 13:18:20',
                'updated_at' => '2022-01-20 13:18:20',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'base_id' => 7,
                'upgrade_id' => 26,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-01-20 13:18:34',
                'updated_at' => '2022-01-20 13:18:34',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'base_id' => 7,
                'upgrade_id' => 27,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-01-20 13:18:47',
                'updated_at' => '2022-01-20 13:18:47',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'base_id' => 6,
                'upgrade_id' => 28,
                'description' => 'Breakfast Included',
                'created_at' => '2022-01-20 13:19:12',
                'updated_at' => '2022-01-20 13:19:12',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'base_id' => 6,
                'upgrade_id' => 29,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-01-20 13:19:23',
                'updated_at' => '2022-01-20 13:19:23',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'base_id' => 6,
                'upgrade_id' => 30,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-01-20 13:19:33',
                'updated_at' => '2022-01-20 13:19:33',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'base_id' => 5,
                'upgrade_id' => 31,
                'description' => 'Breakfast Included',
                'created_at' => '2022-01-20 13:22:35',
                'updated_at' => '2022-01-20 13:22:35',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'base_id' => 5,
                'upgrade_id' => 32,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-01-20 13:22:46',
                'updated_at' => '2022-01-20 13:22:46',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'base_id' => 5,
                'upgrade_id' => 33,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-01-20 13:22:57',
                'updated_at' => '2022-01-20 13:22:57',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'base_id' => 4,
                'upgrade_id' => 34,
                'description' => 'Breakfast Included',
                'created_at' => '2022-01-20 13:23:35',
                'updated_at' => '2022-01-20 13:23:35',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'base_id' => 4,
                'upgrade_id' => 35,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-01-20 13:23:47',
                'updated_at' => '2022-01-20 13:23:47',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'base_id' => 4,
                'upgrade_id' => 36,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-01-20 13:24:00',
                'updated_at' => '2022-01-20 13:24:00',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'base_id' => 46,
                'upgrade_id' => 47,
                'description' => 'Breakfast Included',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:49',
                'deleted_at' => '2022-09-14 09:53:49',
            ),
            28 => 
            array (
                'id' => 29,
                'base_id' => 46,
                'upgrade_id' => 48,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:49',
                'deleted_at' => '2022-09-14 09:53:49',
            ),
            29 => 
            array (
                'id' => 30,
                'base_id' => 46,
                'upgrade_id' => 49,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:49',
                'deleted_at' => '2022-09-14 09:53:49',
            ),
            30 => 
            array (
                'id' => 31,
                'base_id' => 50,
                'upgrade_id' => 51,
                'description' => 'Breakfast Included',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:54:00',
                'deleted_at' => '2022-09-14 09:54:00',
            ),
            31 => 
            array (
                'id' => 32,
                'base_id' => 50,
                'upgrade_id' => 52,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:54:00',
                'deleted_at' => '2022-09-14 09:54:00',
            ),
            32 => 
            array (
                'id' => 33,
                'base_id' => 50,
                'upgrade_id' => 53,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:54:00',
                'deleted_at' => '2022-09-14 09:54:00',
            ),
            33 => 
            array (
                'id' => 34,
                'base_id' => 54,
                'upgrade_id' => 55,
                'description' => 'Breakfast Included',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:51:21',
                'deleted_at' => '2022-09-14 09:51:21',
            ),
            34 => 
            array (
                'id' => 35,
                'base_id' => 54,
                'upgrade_id' => 56,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:51:21',
                'deleted_at' => '2022-09-14 09:51:21',
            ),
            35 => 
            array (
                'id' => 36,
                'base_id' => 54,
                'upgrade_id' => 57,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:51:21',
                'deleted_at' => '2022-09-14 09:51:21',
            ),
            36 => 
            array (
                'id' => 37,
                'base_id' => 58,
                'upgrade_id' => 59,
                'description' => 'Breakfast Included',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:52:31',
                'deleted_at' => '2022-09-14 09:52:31',
            ),
            37 => 
            array (
                'id' => 38,
                'base_id' => 58,
                'upgrade_id' => 60,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:52:31',
                'deleted_at' => '2022-09-14 09:52:31',
            ),
            38 => 
            array (
                'id' => 39,
                'base_id' => 58,
                'upgrade_id' => 61,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:52:31',
                'deleted_at' => '2022-09-14 09:52:31',
            ),
            39 => 
            array (
                'id' => 40,
                'base_id' => 62,
                'upgrade_id' => 63,
                'description' => 'Breakfast Included',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:52:52',
                'deleted_at' => '2022-09-14 09:52:52',
            ),
            40 => 
            array (
                'id' => 41,
                'base_id' => 62,
                'upgrade_id' => 64,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:52:52',
                'deleted_at' => '2022-09-14 09:52:52',
            ),
            41 => 
            array (
                'id' => 42,
                'base_id' => 62,
                'upgrade_id' => 65,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:52:52',
                'deleted_at' => '2022-09-14 09:52:52',
            ),
            42 => 
            array (
                'id' => 43,
                'base_id' => 66,
                'upgrade_id' => 67,
                'description' => 'Breakfast Included',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:04',
                'deleted_at' => '2022-09-14 09:53:04',
            ),
            43 => 
            array (
                'id' => 44,
                'base_id' => 66,
                'upgrade_id' => 68,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:04',
                'deleted_at' => '2022-09-14 09:53:04',
            ),
            44 => 
            array (
                'id' => 45,
                'base_id' => 66,
                'upgrade_id' => 69,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:04',
                'deleted_at' => '2022-09-14 09:53:04',
            ),
            45 => 
            array (
                'id' => 46,
                'base_id' => 70,
                'upgrade_id' => 71,
                'description' => 'Breakfast Included',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:18',
                'deleted_at' => '2022-09-14 09:53:18',
            ),
            46 => 
            array (
                'id' => 47,
                'base_id' => 70,
                'upgrade_id' => 72,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:18',
                'deleted_at' => '2022-09-14 09:53:18',
            ),
            47 => 
            array (
                'id' => 48,
                'base_id' => 70,
                'upgrade_id' => 73,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:18',
                'deleted_at' => '2022-09-14 09:53:18',
            ),
            48 => 
            array (
                'id' => 49,
                'base_id' => 74,
                'upgrade_id' => 75,
                'description' => 'Breakfast Included',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:29',
                'deleted_at' => '2022-09-14 09:53:29',
            ),
            49 => 
            array (
                'id' => 50,
                'base_id' => 74,
                'upgrade_id' => 76,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:29',
                'deleted_at' => '2022-09-14 09:53:29',
            ),
            50 => 
            array (
                'id' => 51,
                'base_id' => 74,
                'upgrade_id' => 77,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:29',
                'deleted_at' => '2022-09-14 09:53:29',
            ),
            51 => 
            array (
                'id' => 52,
                'base_id' => 78,
                'upgrade_id' => 79,
                'description' => 'Breakfast Included',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:39',
                'deleted_at' => '2022-09-14 09:53:39',
            ),
            52 => 
            array (
                'id' => 53,
                'base_id' => 78,
                'upgrade_id' => 80,
                'description' => 'Upgrade to a Single Room',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:39',
                'deleted_at' => '2022-09-14 09:53:39',
            ),
            53 => 
            array (
                'id' => 54,
                'base_id' => 78,
                'upgrade_id' => 81,
            'description' => 'Upgrade to a Single Room (Included Breakfast)',
                'created_at' => '2022-09-14 09:49:12',
                'updated_at' => '2022-09-14 09:53:39',
                'deleted_at' => '2022-09-14 09:53:39',
            ),
            54 => 
            array (
                'id' => 55,
                'base_id' => 100,
                'upgrade_id' => 104,
                'description' => 'High quality breakfast of local produce included, served in the restaurant',
                'created_at' => '2022-09-14 10:10:08',
                'updated_at' => '2022-09-14 10:10:08',
                'deleted_at' => NULL,
            ),
            55 => 
            array (
                'id' => 56,
                'base_id' => 101,
                'upgrade_id' => 105,
                'description' => 'High quality breakfast of local produce included, served in the restaurant',
                'created_at' => '2022-09-14 10:10:41',
                'updated_at' => '2022-09-14 10:10:41',
                'deleted_at' => NULL,
            ),
            56 => 
            array (
                'id' => 57,
                'base_id' => 116,
                'upgrade_id' => 118,
                'description' => 'Upgrade to Fully Catered Deluxe',
                'created_at' => '2023-09-21 13:58:20',
                'updated_at' => '2023-09-21 13:58:20',
                'deleted_at' => NULL,
            ),
            57 => 
            array (
                'id' => 58,
                'base_id' => 117,
                'upgrade_id' => 119,
                'description' => 'Upgrade to Fully Catered Deluxe',
                'created_at' => '2023-09-21 13:58:42',
                'updated_at' => '2023-09-21 13:58:42',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}