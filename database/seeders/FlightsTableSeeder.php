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
                'is_domestic' => 1,
                'notes' => NULL,
                'currency_id' => 46,
                'available_after' => '2021-11-23',
                'created_at' => '2021-11-22 13:11:51',
                'updated_at' => '2021-11-22 13:11:51',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'airline_id' => 2,
                'departure_airport_id' => 1,
                'arrival_airport_id' => 3,
                'is_domestic' => 1,
                'notes' => NULL,
                'currency_id' => 46,
                'available_after' => '2021-11-30',
                'created_at' => '2021-11-30 09:36:03',
                'updated_at' => '2021-11-30 09:36:03',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'airline_id' => 1,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 2,
                'is_domestic' => 1,
                'notes' => NULL,
                'currency_id' => 46,
                'available_after' => '2021-11-23',
                'created_at' => '2021-11-30 10:03:13',
                'updated_at' => '2021-11-30 10:04:00',
                'deleted_at' => '2021-11-30 10:04:00',
            ),
            3 => 
            array (
                'id' => 4,
                'airline_id' => 1,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 1,
                'is_domestic' => 1,
                'notes' => NULL,
                'currency_id' => 46,
                'available_after' => '2021-11-23',
                'created_at' => '2021-11-30 10:04:03',
                'updated_at' => '2021-11-30 10:04:03',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'airline_id' => 2,
                'departure_airport_id' => 3,
                'arrival_airport_id' => 1,
                'is_domestic' => 1,
                'notes' => NULL,
                'currency_id' => 46,
                'available_after' => '2021-11-30',
                'created_at' => '2021-11-30 10:04:38',
                'updated_at' => '2021-11-30 10:04:38',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'airline_id' => 3,
                'departure_airport_id' => 3,
                'arrival_airport_id' => 2,
                'is_domestic' => 1,
                'notes' => NULL,
                'currency_id' => 46,
                'available_after' => '2021-11-30',
                'created_at' => '2021-11-30 11:54:22',
                'updated_at' => '2021-11-30 11:54:22',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'airline_id' => 3,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 3,
                'is_domestic' => 1,
                'notes' => NULL,
                'currency_id' => 46,
                'available_after' => '2021-11-30',
                'created_at' => '2021-11-30 11:54:38',
                'updated_at' => '2021-11-30 11:54:38',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
