<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('transports')->delete();
        DB::table('transports')->insert(array (
            0 => 
            array (
                'id' => 1,
                'transport_type_id' => 1,
                'operator_id' => 1,
                'departure_address_id' => 71,
                'arrival_address_id' => 70,
                'is_domestic' => 1,
                'name' => 'Train to JBR Airport from CPT Airport',
                'image_url' => NULL,
                'description' => 'Train to JBR Airport from CPT Airport',
                'notes' => NULL,
                'currency_id' => 7,
                'created_at' => '2022-01-21 12:42:03',
                'updated_at' => '2022-01-21 12:42:03',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'transport_type_id' => 3,
                'operator_id' => 1,
                'departure_address_id' => 3,
                'arrival_address_id' => 1,
                'is_domestic' => 1,
                'name' => 'Rickshaw from here to there',
                'description' => 'A fun ride to get to there when you are here',
                'notes' => NULL,
                'currency_id' => 46,
                'created_at' => '2021-11-30 12:10:19',
                'updated_at' => '2021-11-30 12:10:44',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'transport_type_id' => 4,
                'operator_id' => 2,
                'departure_address_id' => 5,
                'arrival_address_id' => 4,
                'is_domestic' => 1,
                'name' => 'Another important journey you simply have to take',
                'description' => 'Something for the train spotter',
                'notes' => NULL,
                'currency_id' => 46,
                'created_at' => '2021-11-30 12:10:19',
                'updated_at' => '2021-11-30 12:10:44',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'transport_type_id' => 5,
                'operator_id' => 3,
                'departure_address_id' => 3,
                'arrival_address_id' => 2,
                'is_domestic' => 1,
                'name' => 'Take a helicopter',
                'description' => 'For the transporting passenger',
                'notes' => NULL,
                'currency_id' => 46,
                'created_at' => '2021-11-30 12:10:19',
                'updated_at' => '2021-11-30 12:10:44',
                'deleted_at' => NULL,
            ),
        ));
    }
}
