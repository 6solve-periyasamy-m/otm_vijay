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
        ));
        
        
    }
}
