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
        ));
        
        
    }
}
