<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuoteTransportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('quote_transports')->delete();
        
        \DB::table('quote_transports')->insert(array (
            0 => 
            array (
                'id' => 1,
                'quote_id' => 1,
                'transport_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            1 => 
            array (
                'id' => 2,
                'quote_id' => 2,
                'transport_inventory_id' => 2,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '15.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            2 => 
            array (
                'id' => 3,
                'quote_id' => 3,
                'transport_inventory_id' => 2,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '15.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
        ));
        
        
    }
}