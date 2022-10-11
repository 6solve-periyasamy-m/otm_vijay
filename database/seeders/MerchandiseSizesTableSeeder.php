<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MerchandiseSizesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('merchandise_sizes')->delete();
        
        \DB::table('merchandise_sizes')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Extra Small',
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:02:29',
                'updated_at' => '2022-09-09 08:02:29',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Small',
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:02:34',
                'updated_at' => '2022-09-09 08:02:34',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Medium',
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:02:40',
                'updated_at' => '2022-09-09 08:02:40',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Large',
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:02:46',
                'updated_at' => '2022-09-09 08:02:46',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Extra Large',
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:02:55',
                'updated_at' => '2022-09-09 08:02:55',
            ),
        ));
        
        
    }
}