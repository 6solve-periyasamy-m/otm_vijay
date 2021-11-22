<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class FlightInventoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('flight_inventories')->delete();
        
        \DB::table('flight_inventories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'flight_id' => 1,
                'travel_class_id' => 1,
                'check_in' => '2021-11-22 00:00:00',
                'departs_at' => '2021-11-24 10:00:00',
                'arrives_at' => '2021-11-24 11:30:00',
                'flight_number' => 'BA1121',
                'fit_selectable' => 1,
                'stock' => 150,
                'purchase_price' => 25.0,
                'sales_price' => 75.0,
                'notes' => NULL,
                'created_at' => '2021-11-22 13:12:56',
                'updated_at' => '2021-11-22 13:12:56',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'flight_id' => 1,
                'travel_class_id' => 2,
                'check_in' => '2021-11-22 00:00:00',
                'departs_at' => '2021-11-22 10:00:00',
                'arrives_at' => '2021-11-22 11:30:00',
                'flight_number' => 'BA1121',
                'fit_selectable' => 1,
                'stock' => 25,
                'purchase_price' => 50.0,
                'sales_price' => 150.0,
                'notes' => NULL,
                'created_at' => '2021-11-22 13:16:22',
                'updated_at' => '2021-11-22 13:16:22',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}