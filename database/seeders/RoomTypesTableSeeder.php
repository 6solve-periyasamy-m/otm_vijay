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
                'created_at' => '2022-01-20 11:49:39',
                'updated_at' => '2022-01-20 11:49:39',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Double Room',
                'maximum_occupancy' => 2,
                'created_at' => '2022-01-20 11:59:27',
                'updated_at' => '2022-01-20 11:59:27',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '6 Bed Dorm',
                'maximum_occupancy' => 6,
                'created_at' => '2022-02-09 09:31:12',
                'updated_at' => '2022-02-09 09:31:12',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => '10 Bed Dorm',
                'maximum_occupancy' => 10,
                'created_at' => '2022-02-09 09:31:22',
                'updated_at' => '2022-02-09 09:31:22',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => '4 Bed Dorm',
                'maximum_occupancy' => 4,
                'created_at' => '2022-02-09 09:33:29',
                'updated_at' => '2022-02-09 09:33:29',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Family Suite',
                'maximum_occupancy' => 4,
                'created_at' => '2022-07-05 10:58:41',
                'updated_at' => '2022-07-05 10:58:41',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => '20 Bed Dorm',
                'maximum_occupancy' => 20,
                'created_at' => '2022-08-09 10:36:44',
                'updated_at' => '2022-08-09 10:36:44',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}