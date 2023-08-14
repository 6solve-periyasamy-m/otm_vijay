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
                'purchase_price' => '25.00',
                'sales_price' => '50.00',
                'internal_notes' => NULL,
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
                'purchase_price' => '100.00',
                'sales_price' => '150.00',
                'internal_notes' => NULL,
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
                'purchase_price' => '100.00',
                'sales_price' => '150.00',
                'internal_notes' => NULL,
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
                'purchase_price' => '25.00',
                'sales_price' => '50.00',
                'internal_notes' => NULL,
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
                'purchase_price' => '50.00',
                'sales_price' => '100.00',
                'internal_notes' => NULL,
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
                'purchase_price' => '50.00',
                'sales_price' => '100.00',
                'internal_notes' => NULL,
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
                'purchase_price' => '25.00',
                'sales_price' => '50.00',
                'internal_notes' => NULL,
                'created_at' => '2022-01-21 12:40:49',
                'updated_at' => '2022-01-21 12:43:24',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'flight_id' => 4,
                'travel_class_id' => 1,
                'check_in' => '2023-07-28 23:00:00',
                'departs_at' => '2023-07-29 00:30:00',
                'arrives_at' => '2023-07-29 09:30:00',
                'flight_number' => 'BAXXXX',
                'fit_selectable' => 0,
                'stock' => 50,
                'purchase_price' => '626.00',
                'sales_price' => '974.00',
                'internal_notes' => NULL,
                'created_at' => '2022-09-14 10:19:46',
                'updated_at' => '2022-09-14 10:23:22',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'flight_id' => 5,
                'travel_class_id' => 1,
                'check_in' => '2023-08-02 14:00:00',
                'departs_at' => '2023-08-02 15:30:00',
                'arrives_at' => '2023-08-02 21:30:00',
                'flight_number' => 'BAXXXX',
                'fit_selectable' => 0,
                'stock' => 50,
                'purchase_price' => '599.00',
                'sales_price' => '850.00',
                'internal_notes' => NULL,
                'created_at' => '2022-09-14 10:20:58',
                'updated_at' => '2022-09-14 10:20:58',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'flight_id' => 6,
                'travel_class_id' => 1,
                'check_in' => '2023-10-21 08:00:00',
                'departs_at' => '2023-10-21 10:00:00',
                'arrives_at' => '2023-10-21 16:15:00',
                'flight_number' => 'ENT477',
                'fit_selectable' => 0,
                'stock' => 50,
                'purchase_price' => '299.00',
                'sales_price' => '899.00',
                'internal_notes' => NULL,
                'created_at' => '2022-09-14 12:14:54',
                'updated_at' => '2022-09-14 12:14:54',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'flight_id' => 7,
                'travel_class_id' => 1,
                'check_in' => '2022-11-18 05:00:00',
                'departs_at' => '2022-11-18 08:30:00',
                'arrives_at' => '2022-11-18 15:00:00',
                'flight_number' => 'ZT791',
                'fit_selectable' => 0,
                'stock' => 20,
                'purchase_price' => '350.00',
                'sales_price' => '400.00',
                'internal_notes' => 'Client Advice: Allocated seat numbers may be subject to change to accommodate family groups or if single empty seats are created; Please be aware the airline operates a cash free policy on board and there is no duty free available to purchase; Each infant has a 10kg luggage allowance; Please ensure you are aware of all entry requirements relating to Covid-19 - https://www.gov.uk/foreign-travel-advice/the-gambia/entry-requirements',
                'created_at' => '2022-09-20 18:46:45',
                'updated_at' => '2022-09-20 18:46:45',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'flight_id' => 8,
                'travel_class_id' => 1,
                'check_in' => '2022-11-29 13:00:00',
                'departs_at' => '2022-11-29 16:00:00',
                'arrives_at' => '2022-11-29 22:50:00',
                'flight_number' => 'ZT792',
                'fit_selectable' => 0,
                'stock' => 20,
                'purchase_price' => '350.00',
                'sales_price' => '495.00',
                'internal_notes' => NULL,
                'created_at' => '2022-09-20 18:48:14',
                'updated_at' => '2022-09-20 18:48:14',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
