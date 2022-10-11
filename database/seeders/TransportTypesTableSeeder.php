<?php

namespace Database\Seeders;

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
                'name' => 'Train',
                'created_at' => '2022-01-21 12:41:11',
                'updated_at' => '2022-01-21 12:41:11',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Standard',
                'created_at' => '2022-09-20 19:02:05',
                'updated_at' => '2022-09-20 19:02:05',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Luxury Coach',
                'created_at' => '2022-09-21 08:59:47',
                'updated_at' => '2022-09-21 08:59:47',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}