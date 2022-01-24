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
                'name' => 'Self-Catered',
                'created_at' => '2022-01-20 11:50:05',
                'updated_at' => '2022-01-20 11:50:05',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Bed & Breakfast',
                'created_at' => '2022-01-20 11:59:07',
                'updated_at' => '2022-01-20 11:59:07',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
