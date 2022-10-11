<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('events')->delete();
        
        \DB::table('events')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'The Pride',
                'description' => 'This is a description',
                'starts_at' => '2021-07-01',
                'ends_at' => '2021-07-28',
                'booking_url' => 'the-pride-event',
                'notes' => NULL,
                'created_at' => '2022-01-20 11:28:17',
                'updated_at' => '2022-01-20 11:28:17',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Summer Olympics 2024',
                'description' => NULL,
                'starts_at' => '2024-07-26',
                'ends_at' => '2024-08-11',
                'booking_url' => NULL,
                'notes' => NULL,
                'created_at' => '2022-09-14 09:41:49',
                'updated_at' => '2022-09-14 09:41:49',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Roots Homecoming',
                'description' => 'Expats Travelling to Africa',
                'starts_at' => '2023-10-31',
                'ends_at' => '2023-11-14',
                'booking_url' => NULL,
                'notes' => NULL,
                'created_at' => '2022-09-14 12:04:46',
                'updated_at' => '2022-09-14 12:04:46',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}