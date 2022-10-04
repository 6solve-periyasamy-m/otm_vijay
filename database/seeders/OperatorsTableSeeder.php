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
                'name' => 'ZAF Rail',
                'notes' => NULL,
                'created_at' => '2022-01-21 12:41:23',
                'updated_at' => '2022-01-21 12:41:23',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Senegambia Hotel',
                'notes' => NULL,
                'created_at' => '2022-09-20 19:02:23',
                'updated_at' => '2022-09-20 19:02:23',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Local Operator',
                'notes' => NULL,
                'created_at' => '2022-09-21 09:00:04',
                'updated_at' => '2022-09-21 09:00:04',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}