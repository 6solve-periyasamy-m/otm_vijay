<?php

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
                'is_archived' => 0,
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
                'is_archived' => 0,
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
                'is_archived' => 0,
                'created_at' => '2021-02-22 07:41:52',
                'updated_at' => '2021-02-22 07:41:52',
                'deleted_at' => NULL,
                'currency' => '£',
                'available_after' => NULL,
            ),
        ));
        
        
    }
}