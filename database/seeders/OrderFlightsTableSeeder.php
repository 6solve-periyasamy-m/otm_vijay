<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderFlightsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_flights')->delete();
        
        \DB::table('order_flights')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_customer_id' => 1,
                'flight_inventory_tour_id' => 3,
                'created_at' => '2021-11-22 13:50:51',
                'updated_at' => '2021-11-22 13:50:51',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}