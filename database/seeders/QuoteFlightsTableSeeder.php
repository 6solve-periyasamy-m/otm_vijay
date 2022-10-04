<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuoteFlightsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('quote_flights')->delete();
        
        \DB::table('quote_flights')->insert(array (
            0 => 
            array (
                'id' => 1,
                'quote_id' => 1,
                'flight_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'flight_type' => 'Outbound',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            1 => 
            array (
                'id' => 2,
                'quote_id' => 1,
                'flight_inventory_id' => 4,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'flight_type' => 'Inbound',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            2 => 
            array (
                'id' => 3,
                'quote_id' => 1,
                'flight_inventory_id' => 7,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'flight_type' => 'Outbound',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            3 => 
            array (
                'id' => 4,
                'quote_id' => 2,
                'flight_inventory_id' => 8,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '974.00',
                'flight_type' => 'Outbound',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            4 => 
            array (
                'id' => 5,
                'quote_id' => 2,
                'flight_inventory_id' => 9,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '850.00',
                'flight_type' => 'Inbound',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            5 => 
            array (
                'id' => 6,
                'quote_id' => 3,
                'flight_inventory_id' => 8,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '974.00',
                'flight_type' => 'Outbound',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
            6 => 
            array (
                'id' => 7,
                'quote_id' => 3,
                'flight_inventory_id' => 9,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '850.00',
                'flight_type' => 'Inbound',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
        ));
        
        
    }
}