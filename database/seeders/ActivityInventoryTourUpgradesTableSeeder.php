<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ActivityInventoryTourUpgradesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('activity_inventory_tour_upgrades')->delete();
        
        \DB::table('activity_inventory_tour_upgrades')->insert(array (
            0 => 
            array (
                'id' => 1,
                'base_id' => 1,
                'upgrade_id' => 6,
                'description' => 'All you can eat access',
                'created_at' => '2022-01-21 12:06:31',
                'updated_at' => '2022-01-21 12:06:31',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'base_id' => 4,
                'upgrade_id' => 8,
                'description' => 'Full Access',
                'created_at' => '2022-01-21 12:07:07',
                'updated_at' => '2022-01-21 12:07:07',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
