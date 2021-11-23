<?php

namespace Database\Seeders\November21;

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('locations')->delete();
        
        \DB::table('locations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'region_id' => 2,
                'name' => 'London Heathrow Airport',
                'created_at' => '2021-01-15 15:41:56',
                'updated_at' => '2021-01-15 15:41:56',
                'deleted_at' => NULL,
                'location_type_id' => 1,
                'address' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'region_id' => 2,
                'name' => 'London Gatwick Airport',
                'created_at' => '2021-01-15 15:42:08',
                'updated_at' => '2021-01-15 15:42:08',
                'deleted_at' => NULL,
                'location_type_id' => 1,
                'address' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'region_id' => 3,
                'name' => 'Copenhagen Airport',
                'created_at' => '2021-01-15 15:43:21',
                'updated_at' => '2021-01-15 15:43:21',
                'deleted_at' => NULL,
                'location_type_id' => 1,
                'address' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'region_id' => 4,
                'name' => 'Amsterdam Schipol Aiport',
                'created_at' => '2021-01-15 15:45:10',
                'updated_at' => '2021-01-15 15:45:10',
                'deleted_at' => NULL,
                'location_type_id' => 1,
                'address' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'region_id' => 2,
                'name' => 'Premier Inn London Gatwick',
                'created_at' => '2021-01-15 15:53:17',
                'updated_at' => '2021-01-15 15:53:17',
                'deleted_at' => NULL,
                'location_type_id' => 5,
                'address' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'region_id' => 2,
                'name' => 'Hilton London Gatwick',
                'created_at' => '2021-01-15 15:53:32',
                'updated_at' => '2021-01-15 15:53:32',
                'deleted_at' => NULL,
                'location_type_id' => 5,
                'address' => NULL,
            ),
        ));
        
        
    }
}
