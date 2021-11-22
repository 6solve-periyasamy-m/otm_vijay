<?php

namespace Database\Seeders\November21;

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
                'id' => 6,
                'airline_id' => 1,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 3,
                'is_domestic' => 0,
                'notes' => NULL,
                'created_at' => '2021-02-04 23:24:30',
                'updated_at' => '2021-02-04 23:24:30',
                'deleted_at' => NULL,
                'currency' => '£',
                'available_after' => NULL,
            ),
            1 => 
            array (
                'id' => 7,
                'airline_id' => 1,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 1,
                'is_domestic' => 0,
                'notes' => NULL,
                'created_at' => '2021-02-21 14:25:59',
                'updated_at' => '2021-02-21 14:25:59',
                'deleted_at' => NULL,
                'currency' => '£',
                'available_after' => NULL,
            ),
            2 => 
            array (
                'id' => 8,
                'airline_id' => 3,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 3,
                'is_domestic' => 0,
                'notes' => NULL,
                'created_at' => '2021-02-22 07:41:52',
                'updated_at' => '2021-02-22 07:41:52',
                'deleted_at' => NULL,
                'currency' => '£',
                'available_after' => NULL,
            ),
            3 => 
            array (
                'id' => 9,
                'airline_id' => 3,
                'departure_airport_id' => 3,
                'arrival_airport_id' => 2,
                'is_domestic' => 0,
                'notes' => NULL,
                'created_at' => '2021-02-26 08:04:14',
                'updated_at' => '2021-02-26 08:04:14',
                'deleted_at' => NULL,
                'currency' => '£',
                'available_after' => NULL,
            ),
            4 => 
            array (
                'id' => 10,
                'airline_id' => 3,
                'departure_airport_id' => 3,
                'arrival_airport_id' => 4,
                'is_domestic' => 0,
                'notes' => NULL,
                'created_at' => '2021-02-26 08:04:42',
                'updated_at' => '2021-02-26 08:04:42',
                'deleted_at' => NULL,
                'currency' => '£',
                'available_after' => NULL,
            ),
            5 => 
            array (
                'id' => 11,
                'airline_id' => 3,
                'departure_airport_id' => 4,
                'arrival_airport_id' => 3,
                'is_domestic' => 0,
                'notes' => NULL,
                'created_at' => '2021-02-26 08:06:09',
                'updated_at' => '2021-02-26 08:06:09',
                'deleted_at' => NULL,
                'currency' => '£',
                'available_after' => NULL,
            ),
            6 => 
            array (
                'id' => 12,
                'airline_id' => 2,
                'departure_airport_id' => 4,
                'arrival_airport_id' => 1,
                'is_domestic' => 0,
                'notes' => NULL,
                'created_at' => '2021-03-08 07:51:24',
                'updated_at' => '2021-03-08 07:51:24',
                'deleted_at' => NULL,
                'currency' => '£',
                'available_after' => NULL,
            ),
            7 => 
            array (
                'id' => 13,
                'airline_id' => 2,
                'departure_airport_id' => 1,
                'arrival_airport_id' => 2,
                'is_domestic' => 0,
                'notes' => NULL,
                'created_at' => '2021-03-08 07:57:25',
                'updated_at' => '2021-03-08 07:57:25',
                'deleted_at' => NULL,
                'currency' => 'UKP',
                'available_after' => NULL,
            ),
            8 => 
            array (
                'id' => 14,
                'airline_id' => 2,
                'departure_airport_id' => 1,
                'arrival_airport_id' => 4,
                'is_domestic' => 0,
                'notes' => NULL,
                'created_at' => '2021-03-08 07:58:01',
                'updated_at' => '2021-03-08 07:58:01',
                'deleted_at' => NULL,
                'currency' => '£',
                'available_after' => '2021-03-31',
            ),
        ));
        
        
    }
}
