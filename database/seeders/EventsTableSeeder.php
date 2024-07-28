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
                'tax_bracket_id' => NULL,
                'image_url' => 'images/events/events_1.jpg',
                'event_category' => 0,
                'brand_id' => NULL,
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
                'tax_bracket_id' => NULL,
                'image_url' => 'images/events/events_2.png',
                'event_category' => 0,
                'brand_id' => NULL,
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
                'tax_bracket_id' => NULL,
                'image_url' => 'images/events/events_3.jpg',
                'event_category' => 0,
                'brand_id' => NULL,
            ),
            3 => 
            array (
                'id' => 7,
                'name' => 'Grand Prix',
                'description' => 'The Grand Prix',
                'starts_at' => '2024-10-01',
                'ends_at' => '2024-10-05',
                'booking_url' => NULL,
                'notes' => NULL,
                'created_at' => '2024-07-28 12:49:05',
                'updated_at' => '2024-07-28 13:26:50',
                'deleted_at' => NULL,
                'tax_bracket_id' => 1,
                'image_url' => 'images/events/grandprix.jpg',
                'event_category' => 1,
                'brand_id' => NULL,
            ),
            4 => 
            array (
                'id' => 8,
                'name' => 'Grand Prix - 2024',
                'description' => 'The grand prix - 2024',
                'starts_at' => '2024-10-01',
                'ends_at' => '2024-05-03',
                'booking_url' => NULL,
                'notes' => NULL,
                'created_at' => '2024-07-28 13:33:35',
                'updated_at' => '2024-07-28 13:33:35',
                'deleted_at' => NULL,
                'tax_bracket_id' => 1,
                'image_url' => 'images/events/grandprix.jpg',
                'event_category' => 0,
                'brand_id' => NULL,
            ),
        ));
        
        
    }
}