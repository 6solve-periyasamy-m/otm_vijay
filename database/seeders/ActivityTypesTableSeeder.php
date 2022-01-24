<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('activity_types')->delete();
        DB::table('activity_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Sightseeing',
                'created_at' => '2022-01-20 13:28:41',
                'updated_at' => '2022-01-20 13:28:41',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Guided City Tour',
                'created_at' => '2021-09-10 09:23:19',
                'updated_at' => '2021-09-10 09:23:19',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
