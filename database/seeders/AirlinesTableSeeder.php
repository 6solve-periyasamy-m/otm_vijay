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
                'name' => 'ZAF Local Air',
                'created_at' => '2022-01-21 12:26:13',
                'updated_at' => '2022-09-23 09:48:09',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'British Airways',
                'created_at' => '2022-09-14 10:18:23',
                'updated_at' => '2022-09-23 09:53:59',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Titan Airways',
                'created_at' => '2022-09-20 18:42:34',
                'updated_at' => '2022-09-20 18:42:34',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'EasyJet',
                'created_at' => '2022-09-23 09:36:37',
                'updated_at' => '2022-09-23 09:53:40',
                'deleted_at' => '2022-09-23 09:53:40',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Norwegian Airlines',
                'created_at' => '2022-09-23 09:57:53',
                'updated_at' => '2022-09-23 09:57:53',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}