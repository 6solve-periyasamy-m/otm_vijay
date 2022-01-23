<?php

namespace Database\Seeders;

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
                'name' => 'London Heathrow Airport',
                'address_id' => 69,
                'iata_code' => 'LHR',
                'created_at' => '2022-01-21 11:17:13',
                'updated_at' => '2022-01-21 11:17:13',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'O.R. Tambo International Airport',
                'address_id' => 70,
                'iata_code' => 'JNB',
                'created_at' => '2022-01-21 11:19:21',
                'updated_at' => '2022-01-21 11:19:21',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Cape Town International Airport',
                'address_id' => 71,
                'iata_code' => 'CPT',
                'created_at' => '2022-01-21 12:32:40',
                'updated_at' => '2022-01-21 12:32:40',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
