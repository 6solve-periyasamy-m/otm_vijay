<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TravelClassesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('travel_classes')->delete();
        
        \DB::table('travel_classes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Economy',
                'created_at' => '2022-01-21 11:20:00',
                'updated_at' => '2022-01-21 11:20:00',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'First Class',
                'created_at' => '2022-01-21 11:21:20',
                'updated_at' => '2022-01-21 11:21:20',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Included Meal',
                'created_at' => '2022-01-21 11:21:41',
                'updated_at' => '2022-01-21 11:21:41',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
