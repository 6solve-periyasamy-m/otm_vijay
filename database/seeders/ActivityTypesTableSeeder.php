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
            1 => 
            array (
                'id' => 2,
                'name' => 'Cricket Match',
                'created_at' => '2022-09-14 10:11:58',
                'updated_at' => '2022-09-14 10:11:58',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Fine Dining Experience',
                'created_at' => '2023-09-21 14:46:11',
                'updated_at' => '2023-09-21 14:46:20',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Excursion',
                'created_at' => '2022-09-20 10:54:04',
                'updated_at' => '2022-09-20 10:54:04',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Horse Racing',
                'created_at' => '2022-09-21 08:51:54',
                'updated_at' => '2022-09-21 08:51:54',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}