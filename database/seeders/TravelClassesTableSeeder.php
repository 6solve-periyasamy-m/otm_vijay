<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TravelClassesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('travel_classes')->delete();
        
        \DB::table('travel_classes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Economy',
                'created_at' => '2021-04-02 16:51:57',
                'updated_at' => '2021-04-02 16:51:57',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'First Class',
                'created_at' => '2021-04-02 16:52:15',
                'updated_at' => '2021-04-02 16:52:15',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
