<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ActivityTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('activity_types')->delete();
        
        \DB::table('activity_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Sightseeing',
                'created_at' => '2022-01-20 13:28:41',
                'updated_at' => '2022-01-20 13:28:41',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
