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
                'address_id' => 4,
                'iata_code' => 'LHR',
                'created_at' => '2021-11-22 12:50:10',
                'updated_at' => '2021-11-22 12:50:10',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Leeds Bradford Airport',
                'address_id' => 5,
                'iata_code' => 'LBA',
                'created_at' => '2021-11-22 12:52:48',
                'updated_at' => '2021-11-22 12:52:48',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Birmingham Airport',
                'address_id' => 8,
                'iata_code' => 'BHX',
                'created_at' => '2021-11-30 09:35:40',
                'updated_at' => '2021-11-30 09:35:40',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
