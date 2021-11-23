<?php

namespace Database\Seeders;

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
                'id' => 1,
                'name' => 'Full Board',
                'created_at' => '2021-09-10 08:58:48',
                'updated_at' => '2021-09-10 08:58:48',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Half Board',
                'created_at' => '2021-09-10 08:58:57',
                'updated_at' => '2021-09-10 08:58:57',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Self Catered',
                'created_at' => '2021-09-10 08:59:00',
                'updated_at' => '2021-09-10 08:59:00',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Bed & Breakfast',
                'created_at' => '2021-09-10 08:59:20',
                'updated_at' => '2021-09-10 08:59:20',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
