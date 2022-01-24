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
        ));
        
        
    }
}
