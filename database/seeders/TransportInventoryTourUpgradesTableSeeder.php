<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TransportInventoryTourUpgradesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('transport_inventory_tour_upgrades')->delete();
        
        \DB::table('transport_inventory_tour_upgrades')->insert(array (
            0 => 
            array (
                'id' => 1,
                'base_id' => 9,
                'upgrade_id' => 11,
                'description' => 'Upgrade to a Private Luxury Taxi instead of Coach transfer',
                'created_at' => '2023-09-22 13:15:43',
                'updated_at' => '2023-09-22 13:16:10',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}