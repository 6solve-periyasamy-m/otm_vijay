<?php

namespace Database\Seeders\November21;

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
                'title' => 'The World Cup',
                'description' => 'Travel with your friends to the best seats for the money and watch all the games',
                'notes' => 'ATOL Certified tour',
                'base_price_per_person' => 3000.0,
                'margin' => 500.0,
                'single_occupancy_surcharge' => 100.0,
                'stock_control_active' => 1,
                'stock' => 200,
                'booking_form_url' => 'example-tour',
                'tour_category_id' => NULL,
                'tour_merchandise_id' => NULL,
                'is_active' => 1,
                'date_from' => '2023-03-15',
                'date_to' => '2023-04-25',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'event_id' => 1,
                'title' => 'World Cup Specials',
                'description' => 'Discounted Travel with your friends to the best seats for the money and watch all the games',
                'notes' => 'ATOL Certified tour',
                'base_price_per_person' => 2000.0,
                'margin' => 300.0,
                'single_occupancy_surcharge' => 100.0,
                'stock_control_active' => 1,
                'stock' => 20,
                'booking_form_url' => 'world-cup',
                'tour_category_id' => NULL,
                'tour_merchandise_id' => NULL,
                'is_active' => 0,
                'date_from' => '2023-03-15',
                'date_to' => '2023-04-20',
                'created_at' => NULL,
                'updated_at' => NULL,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'event_id' => NULL,
                'title' => 'Test tour',
                'description' => 'test tour description',
                'notes' => NULL,
                'base_price_per_person' => NULL,
                'margin' => NULL,
                'single_occupancy_surcharge' => NULL,
                'stock_control_active' => NULL,
                'stock' => NULL,
                'booking_form_url' => NULL,
                'tour_category_id' => NULL,
                'tour_merchandise_id' => NULL,
                'is_active' => 0,
                'date_from' => NULL,
                'date_to' => NULL,
                'created_at' => '2021-03-24 07:07:18',
                'updated_at' => '2021-03-24 07:07:18',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
