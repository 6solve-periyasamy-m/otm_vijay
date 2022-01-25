<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ToursTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('tours')->delete();
        DB::table('tours')->insert(array (
            0 => 
            array (
                'id' => 1,
                'event_id' => 1,
                'name' => 'Double Test - The Impala',
            'description' => 'Double Test - The Impala (Tests 2 & 3)',
                'notes' => NULL,
                'base_price_per_person' => 3498.0,
                'margin' => 0.0,
                'single_occupancy_surcharge' => 419.67,
                'deposit' => 300.0,
                'stock_control_active' => 1,
                'stock' => 50,
                'booking_form_url' => 'double-test-impala-pride',
                'tour_category_id' => NULL,
                'tour_merchandise_id' => NULL,
                'is_active' => 1,
                'date_from' => '2022-07-28',
                'date_to' => '2022-08-09',
                'created_at' => '2022-01-20 11:32:05',
                'updated_at' => '2022-01-21 12:05:20',
                'deleted_at' => NULL,
                'invoice_footer' => 'This is a demo tour, and will not be fulfilled',
                'final_payment' => '2022-06-30',
            ),
            1 => 
            array (
                'id' => 2,
                'event_id' => 2,
                'name' => 'The World Cup',
                'description' => 'The Rugby World Cup 2022',
                'notes' => NULL,
                'base_price_per_person' => 4550.0,
                'margin' => 25.0,
                'single_occupancy_surcharge' => 130.0,
                'deposit' => 1500.0,
                'stock_control_active' => 1,
                'stock' => 50,
                'booking_form_url' => 'world-cup',
                'tour_colour_id' => NULL,
                'tour_merchandise_id' => NULL,
                'is_active' => 1,
                'date_from' => '2022-02-01',
                'date_to' => '2022-02-23',
                'created_at' => '2021-11-22 13:28:39',
                'updated_at' => '2021-11-22 13:31:25',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'event_id' => 2,
                'name' => 'The World Cup',
                'description' => 'The Rugby World Cup 2022',
                'notes' => NULL,
                'base_price_per_person' => 4550.0,
                'margin' => 25.0,
                'single_occupancy_surcharge' => 130.0,
                'deposit' => 1500.0,
                'stock_control_active' => 1,
                'stock' => 50,
                'booking_form_url' => 'world-cup',
                'tour_colour_id' => NULL,
                'tour_merchandise_id' => NULL,
                'is_active' => 1,
                'date_from' => '2022-02-01',
                'date_to' => '2022-02-23',
                'created_at' => '2021-11-22 13:28:39',
                'updated_at' => '2021-11-22 13:31:25',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
