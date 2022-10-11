<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TransportInventoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('transport_inventories')->delete();
        
        \DB::table('transport_inventories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'transport_id' => 1,
                'travel_class_id' => 1,
                'departs_at' => '2022-08-04 14:00:00',
                'arrives_at' => '2022-08-04 18:00:00',
                'fit_selectable' => 1,
                'stock' => 50,
                'purchase_price' => '25.00',
                'sales_price' => '50.00',
                'notes' => NULL,
                'arrival_time_confirmed' => 1,
                'departure_time_confirmed' => 1,
                'created_at' => '2022-01-21 12:42:53',
                'updated_at' => '2022-01-21 12:42:53',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'transport_id' => 2,
                'travel_class_id' => 1,
                'departs_at' => '2023-07-29 10:30:00',
                'arrives_at' => '2023-07-29 11:00:00',
                'fit_selectable' => 0,
                'stock' => 50,
                'purchase_price' => '8.00',
                'sales_price' => '15.00',
                'notes' => NULL,
                'arrival_time_confirmed' => 0,
                'departure_time_confirmed' => 0,
                'created_at' => '2022-09-14 10:48:49',
                'updated_at' => '2022-09-14 10:48:49',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'transport_id' => 4,
                'travel_class_id' => 4,
                'departs_at' => '2022-11-18 15:20:00',
                'arrives_at' => '2022-11-18 15:45:00',
                'fit_selectable' => 0,
                'stock' => 20,
                'purchase_price' => '5.00',
                'sales_price' => '10.00',
                'notes' => NULL,
                'arrival_time_confirmed' => 1,
                'departure_time_confirmed' => 1,
                'created_at' => '2022-09-20 19:06:35',
                'updated_at' => '2022-09-20 19:06:35',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'transport_id' => 5,
                'travel_class_id' => 4,
                'departs_at' => '2022-11-29 12:00:00',
                'arrives_at' => '2022-11-29 12:30:00',
                'fit_selectable' => 0,
                'stock' => 20,
                'purchase_price' => '5.00',
                'sales_price' => '10.00',
                'notes' => NULL,
                'arrival_time_confirmed' => 1,
                'departure_time_confirmed' => 1,
                'created_at' => '2022-09-20 19:10:35',
                'updated_at' => '2022-09-20 19:10:35',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'transport_id' => 6,
                'travel_class_id' => 2,
                'departs_at' => '2023-06-22 09:30:00',
                'arrives_at' => '2023-06-22 10:30:00',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '15.00',
                'sales_price' => '30.00',
                'notes' => NULL,
                'arrival_time_confirmed' => 1,
                'departure_time_confirmed' => 1,
                'created_at' => '2022-09-21 09:03:07',
                'updated_at' => '2022-09-21 09:03:07',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'transport_id' => 7,
                'travel_class_id' => 2,
                'departs_at' => '2023-06-22 19:30:00',
                'arrives_at' => '2023-06-22 20:30:00',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '15.00',
                'sales_price' => '35.00',
                'notes' => NULL,
                'arrival_time_confirmed' => 1,
                'departure_time_confirmed' => 1,
                'created_at' => '2022-09-21 09:04:34',
                'updated_at' => '2022-09-21 09:04:34',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}