<?php

namespace Database\Seeders\November21;
use Illuminate\Database\Seeder;

class BoardTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('board_types')->delete();
        
        \DB::table('board_types')->insert(array (
            0 => 
            array (
                'name' => 'Full Board',
                'created_at' => '2021-09-10 08:58:48',
                'deleted_at' => NULL,
                'id' => 1,
                'updated_at' => '2021-09-10 08:58:48',
            ),
            1 => 
            array (
                'name' => 'Half Board',
                'created_at' => '2021-09-10 08:58:57',
                'deleted_at' => NULL,
                'id' => 2,
                'updated_at' => '2021-09-10 08:58:57',
            ),
            2 => 
            array (
                'name' => 'Self Catered',
                'created_at' => '2021-09-10 08:59:00',
                'deleted_at' => NULL,
                'id' => 3,
                'updated_at' => '2021-09-10 08:59:00',
            ),
            3 => 
            array (
                'name' => 'Bed & Breakfast',
                'created_at' => '2021-09-10 08:59:20',
                'deleted_at' => NULL,
                'id' => 4,
                'updated_at' => '2021-09-10 08:59:20',
            ),
        ));
        
        
    }
}
