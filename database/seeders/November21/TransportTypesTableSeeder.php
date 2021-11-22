<?php

namespace Database\Seeders\November21;

use Illuminate\Database\Seeder;

class TransportTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('transport_types')->delete();
        
        \DB::table('transport_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Bus',
                'created_at' => '2021-01-15 15:58:19',
                'updated_at' => '2021-01-15 15:58:19',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Train',
                'created_at' => '2021-01-15 15:58:24',
                'updated_at' => '2021-01-15 15:58:24',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Ferry',
                'created_at' => '2021-01-15 15:58:29',
                'updated_at' => '2021-01-15 15:58:29',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Coach',
                'created_at' => '2021-01-15 15:58:35',
                'updated_at' => '2021-01-15 15:58:35',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Taxi',
                'created_at' => '2021-01-15 15:58:41',
                'updated_at' => '2021-01-15 15:58:41',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Minibus',
                'created_at' => '2021-01-15 15:58:48',
                'updated_at' => '2021-01-15 15:58:48',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Tuk-Tuk',
                'created_at' => '2021-01-15 15:59:08',
                'updated_at' => '2021-01-15 15:59:08',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
