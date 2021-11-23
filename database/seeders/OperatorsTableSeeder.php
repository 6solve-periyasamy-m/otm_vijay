<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OperatorsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('operators')->delete();
        
        \DB::table('operators')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Northern Rail',
                'notes' => NULL,
                'created_at' => '2021-11-22 12:57:22',
                'updated_at' => '2021-11-22 12:57:22',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
