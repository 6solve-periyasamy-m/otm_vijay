<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LocationTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('location_types')->delete();
        
        \DB::table('location_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Airport',
                'created_at' => '2021-01-15 15:40:29',
                'updated_at' => '2021-01-15 15:40:29',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Sea Port',
                'created_at' => '2021-01-15 15:40:38',
                'updated_at' => '2021-01-15 15:40:38',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Train Station',
                'created_at' => '2021-01-15 15:40:47',
                'updated_at' => '2021-01-15 15:40:47',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Bus Station',
                'created_at' => '2021-01-15 15:40:53',
                'updated_at' => '2021-01-15 15:40:53',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Accommodation',
                'created_at' => '2021-01-15 15:41:00',
                'updated_at' => '2021-01-15 15:41:16',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Point of Interest',
                'created_at' => '2021-01-15 15:41:27',
                'updated_at' => '2021-01-15 15:41:27',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Hotel',
                'created_at' => '2021-11-22 12:35:00',
                'updated_at' => '2021-11-22 12:35:00',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
