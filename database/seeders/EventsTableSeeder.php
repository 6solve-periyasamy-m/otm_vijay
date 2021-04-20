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
                'event_title' => 'The World Cup',
                'event_description' => 'The World Cup attracts fans from all over the world. Witness greatness from great seats.',
                'event_start_date' => '2023-03-17',
                'event_end_date' => '2023-04-20',
                'booking_url' => 'world-cup',
                'notes' => 'This is an example record',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}