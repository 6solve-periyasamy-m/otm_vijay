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
        ));
        
        
    }
}
