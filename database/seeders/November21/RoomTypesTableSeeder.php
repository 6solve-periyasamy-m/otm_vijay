<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoomTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('room_types')->delete();
        
        \DB::table('room_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Single Room',
                'maximum_occupancy' => 1,
                'created_at' => '2021-01-15 15:51:15',
                'updated_at' => '2021-01-15 15:51:15',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Double Room',
                'maximum_occupancy' => 2,
                'created_at' => '2021-01-15 15:51:22',
                'updated_at' => '2021-01-15 15:51:22',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Twin Room',
                'maximum_occupancy' => 2,
                'created_at' => '2021-01-15 15:51:27',
                'updated_at' => '2021-01-15 15:51:27',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
