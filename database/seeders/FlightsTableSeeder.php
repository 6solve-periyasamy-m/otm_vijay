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
                'internal_notes' => NULL,
                'currency_id' => 83,
                'available_from' => '2022-07-08',
                'created_at' => '2022-01-21 11:19:45',
                'updated_at' => '2023-09-21 15:25:37',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'airline_id' => 1,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 1,
                'is_domestic' => 0,
                'image_url' => 'images/flight/flight_3.jpg',
                'internal_notes' => NULL,
                'currency_id' => 83,
                'available_from' => '2022-07-08',
                'created_at' => '2022-01-21 11:21:53',
                'updated_at' => '2023-09-21 15:25:51',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'airline_id' => 2,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 3,
                'is_domestic' => 1,
                'image_url' => 'images/flight/flight_4.jpg',
                'internal_notes' => NULL,
                'currency_id' => 30,
                'available_from' => '2022-01-21',
                'created_at' => '2022-01-21 12:32:53',
                'updated_at' => '2023-09-21 15:26:02',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'airline_id' => 3,
                'departure_airport_id' => 1,
                'arrival_airport_id' => 3,
                'is_domestic' => 0,
                'image_url' => 'images/flight/flight_1.jpg',
                'internal_notes' => NULL,
                'currency_id' => 83,
                'available_from' => '2022-09-14',
                'created_at' => '2022-09-14 10:18:45',
                'updated_at' => '2022-09-14 10:18:45',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'airline_id' => 3,
                'departure_airport_id' => 3,
                'arrival_airport_id' => 1,
                'is_domestic' => 0,
                'image_url' => 'images/flight/flight_1.jpg',
                'internal_notes' => NULL,
                'currency_id' => 83,
                'available_from' => '2022-09-14',
                'created_at' => '2022-09-14 10:20:00',
                'updated_at' => '2022-09-14 10:20:00',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'airline_id' => 3,
                'departure_airport_id' => 1,
                'arrival_airport_id' => 4,
                'is_domestic' => 0,
                'image_url' => 'images/flight/flight_1.jpg',
                'internal_notes' => NULL,
                'currency_id' => 83,
                'available_from' => '2022-09-14',
                'created_at' => '2022-09-14 12:12:41',
                'updated_at' => '2023-09-21 15:26:27',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'airline_id' => 4,
                'departure_airport_id' => 5,
                'arrival_airport_id' => 4,
                'is_domestic' => 0,
                'image_url' => 'images/flight/flight_2.png',
                'internal_notes' => NULL,
                'currency_id' => 83,
                'available_from' => '2022-09-20',
                'created_at' => '2022-09-20 18:45:02',
                'updated_at' => '2022-09-20 18:45:02',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'airline_id' => 4,
                'departure_airport_id' => 4,
                'arrival_airport_id' => 5,
                'is_domestic' => 0,
                'image_url' => 'images/flight/flight_2.png',
                'internal_notes' => NULL,
                'currency_id' => 83,
                'available_from' => '2022-09-20',
                'created_at' => '2022-09-20 18:47:14',
                'updated_at' => '2022-09-20 18:47:14',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'airline_id' => 2,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 6,
                'is_domestic' => 0,
                'image_url' => NULL,
                'internal_notes' => NULL,
                'currency_id' => 30,
                'available_from' => '2023-09-22',
                'created_at' => '2023-09-22 12:25:03',
                'updated_at' => '2023-09-22 12:25:03',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'airline_id' => 2,
                'departure_airport_id' => 6,
                'arrival_airport_id' => 2,
                'is_domestic' => 0,
                'image_url' => NULL,
                'internal_notes' => NULL,
                'currency_id' => 30,
                'available_from' => '2023-09-22',
                'created_at' => '2023-09-22 12:29:39',
                'updated_at' => '2023-09-22 12:29:39',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
        ));
        
        
    }
}