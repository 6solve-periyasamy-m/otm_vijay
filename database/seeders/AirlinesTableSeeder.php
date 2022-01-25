<?php

namespace Database\Seeders;

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
                'name' => 'RyanAir',
                'created_at' => '2022-01-21 11:14:12',
                'updated_at' => '2022-01-21 11:14:12',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'ZAF Local',
                'created_at' => '2022-01-21 12:26:13',
                'updated_at' => '2022-01-21 12:26:13',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
