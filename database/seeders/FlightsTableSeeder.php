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
                'image_url' => 'images/flight/flight_3.jpg',
                'notes' => NULL,
                'currency_id' => 71,
                'available_from' => '2022-07-08',
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
                'image_url' => 'images/flight/flight_3.jpg',
                'notes' => NULL,
                'currency_id' => 71,
                'available_from' => '2022-07-08',
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
                'image_url' => 'images/flight/flight_4.jpg',
                'notes' => NULL,
                'currency_id' => 7,
                'available_from' => '2022-01-21',
                'created_at' => '2022-01-21 12:32:53',
                'updated_at' => '2022-01-21 12:32:53',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'airline_id' => 3,
                'departure_airport_id' => 1,
                'arrival_airport_id' => 3,
                'is_domestic' => 0,
                'image_url' => 'images/flight/flight_1.jpg',
                'notes' => NULL,
                'currency_id' => 83,
                'available_from' => '2022-09-14',
                'created_at' => '2022-09-14 10:18:45',
                'updated_at' => '2022-09-14 10:18:45',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'airline_id' => 3,
                'departure_airport_id' => 3,
                'arrival_airport_id' => 1,
                'is_domestic' => 0,
                'image_url' => 'images/flight/flight_1.jpg',
                'notes' => NULL,
                'currency_id' => 83,
                'available_from' => '2022-09-14',
                'created_at' => '2022-09-14 10:20:00',
                'updated_at' => '2022-09-14 10:20:00',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'airline_id' => 3,
                'departure_airport_id' => 1,
                'arrival_airport_id' => 4,
                'is_domestic' => 0,
                'image_url' => 'images/flight/flight_1.jpg',
                'notes' => NULL,
                'currency_id' => 154,
                'available_from' => '2022-09-14',
                'created_at' => '2022-09-14 12:12:41',
                'updated_at' => '2022-09-14 12:12:41',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'airline_id' => 4,
                'departure_airport_id' => 5,
                'arrival_airport_id' => 4,
                'is_domestic' => 0,
                'image_url' => 'images/flight/flight_2.png',
                'notes' => NULL,
                'currency_id' => 83,
                'available_from' => '2022-09-20',
                'created_at' => '2022-09-20 18:45:02',
                'updated_at' => '2022-09-20 18:45:02',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'airline_id' => 4,
                'departure_airport_id' => 4,
                'arrival_airport_id' => 5,
                'is_domestic' => 0,
                'image_url' => 'images/flight/flight_2.png',
                'notes' => NULL,
                'currency_id' => 83,
                'available_from' => '2022-09-20',
                'created_at' => '2022-09-20 18:47:14',
                'updated_at' => '2022-09-20 18:47:14',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
