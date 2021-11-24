<?php

namespace Database\Seeders\November21;
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
                'name' => 'POI Visit',
                'created_at' => '2021-09-10 09:22:56',
                'updated_at' => '2021-09-10 09:22:56',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Restaurant Visit',
                'created_at' => '2021-09-10 09:23:12',
                'updated_at' => '2021-09-10 09:23:12',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Sports Event',
                'created_at' => '2021-09-10 09:23:19',
                'updated_at' => '2021-09-10 09:23:19',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
