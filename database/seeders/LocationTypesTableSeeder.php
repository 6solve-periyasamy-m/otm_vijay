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
                'name' => 'Hotel',
                'created_at' => '2022-01-20 11:46:33',
                'updated_at' => '2022-01-20 11:46:33',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Beach',
                'created_at' => '2022-01-20 13:31:11',
                'updated_at' => '2022-01-20 13:31:11',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Vineyard',
                'created_at' => '2022-01-20 15:30:46',
                'updated_at' => '2022-01-20 15:30:46',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Restaurant',
                'created_at' => '2022-01-21 10:45:03',
                'updated_at' => '2022-01-21 10:45:03',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Tourist Center',
                'created_at' => '2022-01-21 11:00:43',
                'updated_at' => '2022-01-21 11:00:43',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Airport',
                'created_at' => '2022-01-21 11:15:52',
                'updated_at' => '2022-01-21 11:15:52',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Hiking Spot',
                'created_at' => '2022-01-21 11:15:52',
                'updated_at' => '2022-01-21 11:15:52',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Train Station',
                'created_at' => '2022-07-05 10:52:41',
                'updated_at' => '2022-07-05 10:52:41',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Museum',
                'created_at' => '2022-07-05 10:59:50',
                'updated_at' => '2022-07-05 10:59:50',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Point of Interest',
                'created_at' => '2023-08-01 15:55:23',
                'updated_at' => '2023-08-01 15:55:23',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'Cricket Ground',
                'created_at' => '2022-09-14 10:12:27',
                'updated_at' => '2022-09-14 10:12:27',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'Racecourse',
                'created_at' => '2022-09-21 08:53:35',
                'updated_at' => '2022-09-21 08:53:35',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}