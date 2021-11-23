<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AddressParentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('address_parents')->delete();
        
        \DB::table('address_parents')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Customer',
                'created_at' => '2021-11-22 12:31:15',
                'updated_at' => '2021-11-22 12:31:15',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Accommodation',
                'created_at' => '2021-11-22 12:31:15',
                'updated_at' => '2021-11-22 12:31:15',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Activity',
                'created_at' => '2021-11-22 12:31:15',
                'updated_at' => '2021-11-22 12:31:15',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Airport',
                'created_at' => '2021-11-22 12:31:15',
                'updated_at' => '2021-11-22 12:31:15',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Transport',
                'created_at' => '2021-11-22 12:31:15',
                'updated_at' => '2021-11-22 12:31:15',
            ),
            5 => 
            array (
                'id' => 63,
                'name' => 'Other',
                'created_at' => '2021-11-22 12:31:15',
                'updated_at' => '2021-11-22 12:31:15',
            ),
        ));
        
        
    }
}
