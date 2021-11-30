<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccommodationInventoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('accommodation_inventories')->delete();
        
        \DB::table('accommodation_inventories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'accommodation_id' => 1,
                'room_type_id' => 1,
                'board_type_id' => 1,
                'check_in' => '2021-11-22 10:00:00',
                'check_in_time_confirmed' => 1,
                'check_out' => '2021-11-24 13:00:00',
                'check_out_time_confirmed' => 1,
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 20.0,
                'sales_price' => 50.0,
                'notes' => NULL,
                'created_at' => '2021-11-22 12:38:08',
                'updated_at' => '2021-11-22 12:38:08',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'accommodation_id' => 1,
                'room_type_id' => 2,
                'board_type_id' => 1,
                'check_in' => '2021-11-22 10:00:00',
                'check_in_time_confirmed' => 1,
                'check_out' => '2021-11-25 10:00:00',
                'check_out_time_confirmed' => 1,
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 30.0,
                'sales_price' => 100.0,
                'notes' => NULL,
                'created_at' => '2021-11-22 12:38:49',
                'updated_at' => '2021-11-22 12:38:49',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'accommodation_id' => 2,
                'room_type_id' => 1,
                'board_type_id' => 1,
                'check_in' => '2021-11-22 00:00:00',
                'check_in_time_confirmed' => 1,
                'check_out' => '2021-11-24 00:00:00',
                'check_out_time_confirmed' => 0,
                'fit_selectable' => 1,
                'stock' => 150,
                'purchase_price' => 20.0,
                'sales_price' => 250.0,
                'notes' => NULL,
                'created_at' => '2021-11-22 12:44:00',
                'updated_at' => '2021-11-22 12:44:00',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'accommodation_id' => 1,
                'room_type_id' => 1,
                'board_type_id' => 1,
                'check_in' => '2021-11-22 10:00:00',
                'check_in_time_confirmed' => 1,
                'check_out' => '2021-11-25 10:00:00',
                'check_out_time_confirmed' => 1,
                'fit_selectable' => 1,
                'stock' => 100,
                'purchase_price' => 30.0,
                'sales_price' => 100.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 10:16:56',
                'updated_at' => '2021-11-30 10:17:06',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
