<?php

use Illuminate\Database\Seeder;

class AirportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('airports')->delete();
        
        \DB::table('airports')->insert(array (
            0 => 
            array (
                'id' => 1,
                'airport_name' => 'Amsterdam Schipol Airport',
                'location_id' => 4,
                'iata_code' => 'AMS',
                'created_at' => '2021-01-15 15:45:40',
                'updated_at' => '2021-01-15 15:45:40',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'airport_name' => 'London Gatwick Airport',
                'location_id' => 2,
                'iata_code' => 'LGW',
                'created_at' => '2021-01-15 15:45:54',
                'updated_at' => '2021-01-15 15:45:54',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'airport_name' => 'Copenhagen Airport',
                'location_id' => 3,
                'iata_code' => 'CPH',
                'created_at' => '2021-01-15 15:46:06',
                'updated_at' => '2021-01-15 15:46:06',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'airport_name' => 'London Heathrow Airport',
                'location_id' => 1,
                'iata_code' => 'LHR',
                'created_at' => '2021-01-15 15:46:00',
                'updated_at' => '2021-01-15 15:51:57',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}