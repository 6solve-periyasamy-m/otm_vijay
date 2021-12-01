<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TransportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('transports')->delete();
        
        \DB::table('transports')->insert(array (
            0 => 
            array (
                'id' => 1,
                'transport_type_id' => 2,
                'operator_id' => 1,
                'departure_address_id' => 5,
                'arrival_address_id' => 2,
                'is_domestic' => 1,
                'name' => 'Train from Leeds Bradford Airport to The Queens Hotel',
                'description' => 'Queens Hotel is next door to the train station, so a train ride to Leeds Station will get you to the hotel',
                'notes' => NULL,
                'currency_id' => 46,
                'created_at' => '2021-11-22 12:59:44',
                'updated_at' => '2021-11-22 12:59:44',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'transport_type_id' => 2,
                'operator_id' => 1,
                'departure_address_id' => 2,
                'arrival_address_id' => 5,
                'is_domestic' => 1,
                'name' => 'Train from The Queens Hotel to Leeds Bradford Airport',
                'description' => 'Queens Hotel is next door to the train station, so a train ride to Leeds Station will get you to the hotel',
                'notes' => NULL,
                'currency_id' => 46,
                'created_at' => '2021-11-30 12:10:19',
                'updated_at' => '2021-11-30 12:10:44',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
