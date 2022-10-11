<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FlightInventoryTourUpgradesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('flight_inventory_tour_upgrades')->delete();
        
        \DB::table('flight_inventory_tour_upgrades')->insert(array (
            0 => 
            array (
                'id' => 1,
                'base_id' => 2,
                'upgrade_id' => 5,
                'description' => 'Upgrade to First Class',
                'created_at' => '2022-01-21 12:12:00',
                'updated_at' => '2022-01-21 12:12:00',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'base_id' => 1,
                'upgrade_id' => 6,
                'description' => 'Upgrade to First Class',
                'created_at' => '2022-01-21 12:12:24',
                'updated_at' => '2022-01-21 12:12:24',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'base_id' => 8,
                'upgrade_id' => 9,
                'description' => 'Upgrade to First Class',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:52:00',
                'deleted_at' => '2022-09-14 09:52:00',
            ),
            3 => 
            array (
                'id' => 4,
                'base_id' => 10,
                'upgrade_id' => 11,
                'description' => 'Upgrade to First Class',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:51:51',
                'deleted_at' => '2022-09-14 09:51:51',
            ),
        ));
        
        
    }
}