<?php

namespace Database\Seeders\November21;

use Illuminate\Database\Seeder;

class AirlinesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('airlines')->delete();
        
        \DB::table('airlines')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'British Airways',
                'created_at' => '2021-01-15 15:36:53',
                'updated_at' => '2021-01-15 15:36:53',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'EasyJet',
                'created_at' => '2021-01-15 15:43:42',
                'updated_at' => '2021-01-15 15:43:42',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'KLM',
                'created_at' => '2021-01-15 15:43:50',
                'updated_at' => '2021-01-15 15:43:50',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
