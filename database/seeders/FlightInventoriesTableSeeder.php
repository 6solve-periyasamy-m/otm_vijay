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
                'check_in' => '2022-07-28 09:00:00',
                'departs_at' => '2022-07-28 10:00:00',
                'arrives_at' => '2022-07-28 17:00:00',
                'flight_number' => 'JNBRY0201',
                'fit_selectable' => 1,
                'stock' => 50,
                'purchase_price' => 25.0,
                'sales_price' => 50.0,
                'notes' => NULL,
                'created_at' => '2022-01-21 11:21:08',
                'updated_at' => '2022-01-21 11:21:08',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'flight_id' => 1,
                'travel_class_id' => 2,
                'check_in' => '2022-07-28 09:00:00',
                'departs_at' => '2022-07-28 10:00:00',
                'arrives_at' => '2022-07-28 17:00:00',
                'flight_number' => 'JNBRY0201',
                'fit_selectable' => 1,
                'stock' => 50,
                'purchase_price' => 100.0,
                'sales_price' => 150.0,
                'notes' => NULL,
                'created_at' => '2022-01-21 11:21:11',
                'updated_at' => '2022-01-21 11:21:29',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'flight_id' => 1,
                'travel_class_id' => 3,
                'check_in' => '2022-07-28 09:00:00',
                'departs_at' => '2022-07-28 10:00:00',
                'arrives_at' => '2022-07-28 17:00:00',
                'flight_number' => 'JNBRY0201',
                'fit_selectable' => 1,
                'stock' => 50,
                'purchase_price' => 100.0,
                'sales_price' => 150.0,
                'notes' => NULL,
                'created_at' => '2022-01-21 11:21:34',
                'updated_at' => '2022-01-21 11:21:45',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'flight_id' => 2,
                'travel_class_id' => 1,
                'check_in' => '2022-08-08 09:00:00',
                'departs_at' => '2022-08-08 10:00:00',
                'arrives_at' => '2022-08-08 19:00:00',
                'flight_number' => 'LHRRY09221',
                'fit_selectable' => 1,
                'stock' => 50,
                'purchase_price' => 25.0,
                'sales_price' => 50.0,
                'notes' => NULL,
                'created_at' => '2022-01-21 11:25:40',
                'updated_at' => '2022-01-21 11:25:40',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'flight_id' => 2,
                'travel_class_id' => 2,
                'check_in' => '2022-08-08 09:00:00',
                'departs_at' => '2022-08-08 10:00:00',
                'arrives_at' => '2022-08-08 19:00:00',
                'flight_number' => 'LHRRY09221',
                'fit_selectable' => 1,
                'stock' => 50,
                'purchase_price' => 50.0,
                'sales_price' => 100.0,
                'notes' => NULL,
                'created_at' => '2022-01-21 11:25:43',
                'updated_at' => '2022-01-21 11:25:50',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'flight_id' => 2,
                'travel_class_id' => 3,
                'check_in' => '2022-08-08 09:00:00',
                'departs_at' => '2022-08-08 10:00:00',
                'arrives_at' => '2022-08-08 19:00:00',
                'flight_number' => 'LHRRY09221',
                'fit_selectable' => 1,
                'stock' => 50,
                'purchase_price' => 50.0,
                'sales_price' => 100.0,
                'notes' => NULL,
                'created_at' => '2022-01-21 11:25:53',
                'updated_at' => '2022-01-21 11:25:57',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'flight_id' => 3,
                'travel_class_id' => 1,
                'check_in' => '2022-08-04 09:00:00',
                'departs_at' => '2022-07-29 10:00:00',
                'arrives_at' => '2022-07-29 12:00:00',
                'flight_number' => 'ZAFCPT010123',
                'fit_selectable' => 1,
                'stock' => 50,
                'purchase_price' => 25.0,
                'sales_price' => 50.0,
                'notes' => NULL,
                'created_at' => '2022-01-21 12:40:49',
                'updated_at' => '2022-01-21 12:43:24',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
