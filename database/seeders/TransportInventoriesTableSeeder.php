<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportInventoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('transport_inventories')->delete();
        DB::table('transport_inventories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'transport_id' => 1,
                'travel_class_id' => 1,
                'departs_at' => '2021-11-22 12:00:00',
                'arrives_at' => '2021-11-22 12:30:00',
                'fit_selectable' => 1,
                'stock' => 150,
                'purchase_price' => 15.0,
                'sales_price' => 30.0,
                'notes' => NULL,
                'arrival_time_confirmed' => 1,
                'departure_time_confirmed' => 1,
                'created_at' => '2021-11-22 13:29:28',
                'updated_at' => '2021-11-22 13:29:28',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'transport_id' => 2,
                'travel_class_id' => 1,
                'departs_at' => '2021-11-30 02:00:00',
                'arrives_at' => '2021-11-30 02:00:00',
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 25.0,
                'sales_price' => 40.0,
                'notes' => NULL,
                'arrival_time_confirmed' => 1,
                'departure_time_confirmed' => 1,
                'created_at' => '2021-11-30 12:11:53',
                'updated_at' => '2021-11-30 12:11:53',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'transport_id' => 2,
                'travel_class_id' => 2,
                'departs_at' => '2021-11-30 02:00:00',
                'arrives_at' => '2021-11-30 02:00:00',
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 40.0,
                'sales_price' => 80.0,
                'notes' => NULL,
                'arrival_time_confirmed' => 1,
                'departure_time_confirmed' => 1,
                'created_at' => '2021-11-30 12:12:29',
                'updated_at' => '2021-11-30 12:12:38',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'transport_id' => 3,
                'travel_class_id' => 1,
                'departs_at' => '2021-11-30 02:00:00',
                'arrives_at' => '2021-11-30 02:00:00',
                'fit_selectable' => 1,
                'stock' => 10,
                'purchase_price' => 140.0,
                'sales_price' => 180.0,
                'notes' => NULL,
                'arrival_time_confirmed' => 1,
                'departure_time_confirmed' => 1,
                'created_at' => '2021-11-30 12:12:29',
                'updated_at' => '2021-11-30 12:12:38',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'transport_id' => 4,
                'travel_class_id' => 2,
                'departs_at' => '2021-11-30 02:00:00',
                'arrives_at' => '2021-11-30 02:00:00',
                'fit_selectable' => 1,
                'stock' => 10,
                'purchase_price' => 240.0,
                'sales_price' => 280.0,
                'notes' => NULL,
                'arrival_time_confirmed' => 1,
                'departure_time_confirmed' => 1,
                'created_at' => '2021-11-30 12:12:29',
                'updated_at' => '2021-11-30 12:12:38',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'transport_id' => 5,
                'travel_class_id' => 1,
                'departs_at' => '2021-11-30 02:00:00',
                'arrives_at' => '2021-11-30 02:00:00',
                'fit_selectable' => 1,
                'stock' => 30,
                'purchase_price' => 440.0,
                'sales_price' => 480.0,
                'notes' => NULL,
                'arrival_time_confirmed' => 1,
                'departure_time_confirmed' => 1,
                'created_at' => '2021-11-30 12:12:29',
                'updated_at' => '2021-11-30 12:12:38',
                'deleted_at' => NULL,
            ),
        ));
    }
}
