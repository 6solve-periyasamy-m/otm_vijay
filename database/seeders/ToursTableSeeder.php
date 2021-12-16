<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ToursTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tours')->delete();
        
        \DB::table('tours')->insert(array (
            0 => 
            array (
                'id' => 1,
                'event_id' => 1,
                'name' => 'Trip to Leeds City Museum',
                'description' => 'A trip to view the new Dinosaur Exhibit at Leeds City Museum',
                'notes' => NULL,
                'base_price_per_person' => 250.0,
                'margin' => 10.0,
                'single_occupancy_surcharge' => 100.0,
                'deposit' => 150.0,
                'stock_control_active' => 1,
                'stock' => 150,
                'booking_form_url' => 'dino-tour',
                'tour_category_id' => NULL,
                'tour_merchandise_id' => NULL,
                'is_active' => 1,
                'date_from' => '2021-11-22',
                'date_to' => '2021-11-23',
                'created_at' => '2021-11-22 13:28:39',
                'updated_at' => '2021-11-22 13:31:25',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
