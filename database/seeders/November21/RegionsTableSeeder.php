<?php

namespace Database\Seeders\November21;

use Illuminate\Database\Seeder;

class RegionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('regions')->delete();
        
        \DB::table('regions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Oakham',
                'created_at' => '2021-01-05 10:37:44',
                'updated_at' => '2021-01-05 10:37:44',
                'deleted_at' => NULL,
                'country_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'London',
                'created_at' => '2021-01-15 15:37:56',
                'updated_at' => '2021-01-15 15:37:56',
                'deleted_at' => NULL,
                'country_id' => 1,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Copenhagen',
                'created_at' => '2021-01-15 15:43:06',
                'updated_at' => '2021-01-15 15:43:06',
                'deleted_at' => NULL,
                'country_id' => 2,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Amsterdam',
                'created_at' => '2021-01-15 15:44:39',
                'updated_at' => '2021-01-15 15:44:39',
                'deleted_at' => NULL,
                'country_id' => 3,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Rotterdam',
                'created_at' => '2021-01-15 15:44:47',
                'updated_at' => '2021-01-15 15:44:47',
                'deleted_at' => NULL,
                'country_id' => 3,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Johannesburg',
                'created_at' => '2021-01-19 10:39:43',
                'updated_at' => '2021-01-19 10:39:43',
                'deleted_at' => NULL,
                'country_id' => 4,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Pretoria',
                'created_at' => '2021-01-19 10:40:29',
                'updated_at' => '2021-01-19 10:40:29',
                'deleted_at' => NULL,
                'country_id' => 4,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Pilanesburg',
                'created_at' => '2021-01-19 10:40:53',
                'updated_at' => '2021-01-19 10:40:53',
                'deleted_at' => NULL,
                'country_id' => 4,
            ),
        ));
        
        
    }
}
