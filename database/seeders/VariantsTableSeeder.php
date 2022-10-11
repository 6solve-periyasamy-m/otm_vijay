<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class VariantsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('variants')->delete();
        
        \DB::table('variants')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'White',
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:02:04',
                'updated_at' => '2022-09-09 08:02:04',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Red',
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:02:08',
                'updated_at' => '2022-09-09 08:02:08',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Green',
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:02:14',
                'updated_at' => '2022-09-09 08:02:14',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Yellow',
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:02:20',
                'updated_at' => '2022-09-09 08:02:20',
            ),
        ));
        
        
    }
}