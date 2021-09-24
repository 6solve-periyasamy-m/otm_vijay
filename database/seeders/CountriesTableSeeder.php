<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CountriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('countries')->delete();
        
        \DB::table('countries')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'United Kingdom',
                'country_code' => 'UK',
                'currency' => 'GBP',
                'created_at' => '2021-01-05 10:35:51',
                'updated_at' => '2021-01-05 10:35:51',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Denmark',
                'country_code' => 'DK',
                'currency' => 'DKK',
                'created_at' => '2021-01-15 15:42:59',
                'updated_at' => '2021-01-15 15:42:59',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'The Netherlands',
                'country_code' => 'ND',
                'currency' => 'EUR',
                'created_at' => '2021-01-15 15:44:00',
                'updated_at' => '2021-01-15 15:44:23',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'South Africa',
                'country_code' => 'RSA',
                'currency' => 'ZAR',
                'created_at' => '2021-01-19 10:39:00',
                'updated_at' => '2021-01-19 10:39:14',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
