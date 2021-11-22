<?php

namespace Database\Seeders\November21;

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
                'name' => 'The World Cup',
                'description' => 'The World Cup attracts fans from all over the world. Witness greatness from great seats.',
                'starts_at' => '2023-03-17',
                'ends_at' => '2023-04-20',
                'booking_url' => 'world-cup',
                'notes' => 'This is an example record',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
