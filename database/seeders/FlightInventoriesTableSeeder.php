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
            2 => 
            array (
                'id' => 3,
                'flight_id' => 4,
                'travel_class_id' => 1,
                'check_in' => '2021-11-30 00:00:00',
                'departs_at' => '2021-11-30 00:00:00',
                'arrives_at' => '2021-11-30 01:00:00',
                'flight_number' => 'BAX12341',
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 50.0,
                'sales_price' => 75.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 10:38:52',
                'updated_at' => '2021-11-30 10:38:52',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'flight_id' => 4,
                'travel_class_id' => 2,
                'check_in' => '2021-11-30 00:00:00',
                'departs_at' => '2021-11-30 00:00:00',
                'arrives_at' => '2021-11-30 01:00:00',
                'flight_number' => 'BAX12341',
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 50.0,
                'sales_price' => 75.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 10:39:08',
                'updated_at' => '2021-11-30 10:39:16',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'flight_id' => 7,
                'travel_class_id' => 1,
                'check_in' => '2021-11-30 00:00:00',
                'departs_at' => '2021-11-30 02:00:00',
                'arrives_at' => '2021-11-30 04:00:00',
                'flight_number' => 'KLM95952',
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 50.0,
                'sales_price' => 100.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 11:55:23',
                'updated_at' => '2021-11-30 11:55:23',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'flight_id' => 7,
                'travel_class_id' => 2,
                'check_in' => '2021-11-30 00:00:00',
                'departs_at' => '2021-11-30 02:00:00',
                'arrives_at' => '2021-11-30 04:00:00',
                'flight_number' => 'KLM95952',
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 75.0,
                'sales_price' => 150.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 11:55:26',
                'updated_at' => '2021-11-30 11:55:39',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'flight_id' => 6,
                'travel_class_id' => 1,
                'check_in' => '2021-11-30 00:00:00',
                'departs_at' => '2021-11-30 00:00:00',
                'arrives_at' => '2021-11-30 01:00:00',
                'flight_number' => 'KLM21234',
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 50.0,
                'sales_price' => 100.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 11:58:29',
                'updated_at' => '2021-11-30 11:58:29',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'flight_id' => 6,
                'travel_class_id' => 2,
                'check_in' => '2021-11-30 00:00:00',
                'departs_at' => '2021-11-30 00:00:00',
                'arrives_at' => '2021-11-30 01:00:00',
                'flight_number' => 'KLM21234',
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 50.0,
                'sales_price' => 100.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 11:58:32',
                'updated_at' => '2021-11-30 11:58:35',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'flight_id' => 2,
                'travel_class_id' => 1,
                'check_in' => '2021-11-01 00:00:00',
                'departs_at' => '2021-11-30 00:00:00',
                'arrives_at' => '2021-11-30 00:00:00',
                'flight_number' => 'EAS16512',
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 50.0,
                'sales_price' => 100.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 11:59:18',
                'updated_at' => '2021-11-30 11:59:18',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'flight_id' => 2,
                'travel_class_id' => 2,
                'check_in' => '2021-11-01 00:00:00',
                'departs_at' => '2021-11-30 00:00:00',
                'arrives_at' => '2021-11-30 00:00:00',
                'flight_number' => 'EAS16512',
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 50.0,
                'sales_price' => 100.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 11:59:21',
                'updated_at' => '2021-11-30 11:59:32',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'flight_id' => 5,
                'travel_class_id' => 1,
                'check_in' => '2021-11-30 00:00:00',
                'departs_at' => '2021-11-30 00:00:00',
                'arrives_at' => '2021-11-30 00:00:00',
                'flight_number' => 'EAS16522',
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 50.0,
                'sales_price' => 100.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 12:14:39',
                'updated_at' => '2021-11-30 12:14:39',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'flight_id' => 5,
                'travel_class_id' => 2,
                'check_in' => '2021-11-30 00:00:00',
                'departs_at' => '2021-11-30 00:00:00',
                'arrives_at' => '2021-11-30 00:00:00',
                'flight_number' => 'EAS16522',
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 75.0,
                'sales_price' => 150.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 12:14:43',
                'updated_at' => '2021-11-30 12:14:56',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
