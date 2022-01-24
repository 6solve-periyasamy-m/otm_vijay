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
        ));
        
        
    }
}
