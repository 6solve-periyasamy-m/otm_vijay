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
                'name' => 'Dinosaur Exhibit at Leeds City Museum',
                'description' => 'Leeds City Museum is holding an exibit to show off a skeleton found recently.',
                'starts_at' => '2021-11-22',
                'ends_at' => '2021-11-30',
                'booking_url' => 'dino-museum',
                'notes' => NULL,
                'created_at' => '2021-11-22 13:26:28',
                'updated_at' => '2021-11-22 13:26:28',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
