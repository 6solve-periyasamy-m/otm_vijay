<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccommodationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('accommodations')->delete();
        
        \DB::table('accommodations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Hilton London Kensington',
                'description' => 'Polished lodging with warm rooms and suites, plus a chic restaurant, a lounge and an exercise room.',
                'audit_date' => '2021-11-22',
                'currency_id' => 46,
                'address_id' => 1,
                'created_at' => '2021-11-22 12:36:26',
                'updated_at' => '2021-11-22 12:36:26',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'The Queens Hotel',
                'description' => 'This genteel art deco hotel with a stone facade is 0.2 miles from the Trinity Leeds shopping centre and 2.4 miles from Leeds City Museum.',
                'audit_date' => '2021-11-22',
                'currency_id' => 46,
                'address_id' => 2,
                'created_at' => '2021-11-22 12:43:22',
                'updated_at' => '2021-11-22 12:43:22',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
