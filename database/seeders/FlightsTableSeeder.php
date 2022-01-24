<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FlightsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('flights')->delete();
        
        \DB::table('flights')->insert(array (
            0 => 
            array (
                'id' => 1,
                'airline_id' => 1,
                'departure_airport_id' => 1,
                'arrival_airport_id' => 2,
                'is_domestic' => 0,
                'image_url' => NULL,
                'notes' => NULL,
                'currency_id' => 71,
                'available_after' => '2022-07-08',
                'created_at' => '2022-01-21 11:19:45',
                'updated_at' => '2022-01-21 11:19:45',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'airline_id' => 1,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 1,
                'is_domestic' => 0,
                'image_url' => NULL,
                'notes' => NULL,
                'currency_id' => 71,
                'available_after' => '2022-07-08',
                'created_at' => '2022-01-21 11:21:53',
                'updated_at' => '2022-01-21 11:21:53',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'airline_id' => 2,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 3,
                'is_domestic' => 1,
                'image_url' => NULL,
                'notes' => NULL,
                'currency_id' => 7,
                'available_after' => '2022-01-21',
                'created_at' => '2022-01-21 12:32:53',
                'updated_at' => '2022-01-21 12:32:53',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
