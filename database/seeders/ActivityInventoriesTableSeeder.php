<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityInventoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('activity_inventories')->delete();
        DB::table('activity_inventories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'activity_id' => 1,
                'starts_at' => '2022-07-30 03:00:00',
                'ends_at' => '2022-07-30 10:00:00',
                'fit_selectable' => 1,
                'ticket_type_id' => 1,
                'stock' => 50,
                'purchase_price' => 10.0,
                'sales_price' => 25.0,
                'notes' => NULL,
                'created_at' => '2022-01-20 13:35:31',
                'updated_at' => '2022-01-20 13:35:31',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'activity_id' => 1,
                'starts_at' => '2022-07-30 03:00:00',
                'ends_at' => '2022-07-30 10:00:00',
                'fit_selectable' => 1,
                'ticket_type_id' => 2,
                'stock' => 50,
                'purchase_price' => 25.0,
                'sales_price' => 50.0,
                'notes' => NULL,
                'created_at' => '2022-01-20 13:35:34',
                'updated_at' => '2022-01-20 13:38:08',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'activity_id' => 1,
                'starts_at' => '2022-07-30 03:00:00',
                'ends_at' => '2022-07-30 10:00:00',
                'fit_selectable' => 1,
                'ticket_type_id' => 3,
                'stock' => 50,
                'purchase_price' => 50.0,
                'sales_price' => 75.0,
                'notes' => NULL,
                'created_at' => '2022-01-20 13:36:08',
                'updated_at' => '2022-01-20 13:37:55',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'activity_id' => 2,
                'starts_at' => '2022-08-02 11:00:00',
                'ends_at' => '2022-08-02 03:00:00',
                'fit_selectable' => 1,
                'ticket_type_id' => 1,
                'stock' => 50,
                'purchase_price' => 15.0,
                'sales_price' => 40.0,
                'notes' => NULL,
                'created_at' => '2022-01-20 15:35:37',
                'updated_at' => '2022-01-20 15:35:37',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'activity_id' => 2,
                'starts_at' => '2022-08-02 11:00:00',
                'ends_at' => '2022-08-02 03:00:00',
                'fit_selectable' => 1,
                'ticket_type_id' => 4,
                'stock' => 50,
                'purchase_price' => 25.0,
                'sales_price' => 60.0,
                'notes' => NULL,
                'created_at' => '2022-01-20 15:35:40',
                'updated_at' => '2022-01-20 15:36:36',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'activity_id' => 2,
                'starts_at' => '2022-08-02 03:00:00',
                'ends_at' => '2022-08-02 06:00:00',
                'fit_selectable' => 1,
                'ticket_type_id' => 5,
                'stock' => 50,
                'purchase_price' => 25.0,
                'sales_price' => 60.0,
                'notes' => NULL,
                'created_at' => '2022-01-20 15:37:08',
                'updated_at' => '2022-01-20 15:53:24',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'activity_id' => 3,
                'starts_at' => '2022-08-02 12:00:00',
                'ends_at' => '2022-08-02 05:00:00',
                'fit_selectable' => 1,
                'ticket_type_id' => 1,
                'stock' => 50,
                'purchase_price' => 25.0,
                'sales_price' => 50.0,
                'notes' => NULL,
                'created_at' => '2022-01-21 10:51:09',
                'updated_at' => '2022-01-21 10:51:09',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'activity_id' => 4,
                'starts_at' => '2022-08-06 11:00:00',
                'ends_at' => '2022-08-06 06:00:00',
                'fit_selectable' => 1,
                'ticket_type_id' => 1,
                'stock' => 50,
                'purchase_price' => 25.0,
                'sales_price' => 250.0,
                'notes' => NULL,
                'created_at' => '2022-01-21 11:03:28',
                'updated_at' => '2022-01-21 11:03:28',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'activity_id' => 2,
                'starts_at' => '2021-11-23 10:00:00',
                'ends_at' => '2021-11-23 15:00:00',
                'fit_selectable' => 1,
                'ticket_type_id' => 3,
                'stock' => 15,
                'purchase_price' => 10.0,
                'sales_price' => 45.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 10:18:06',
                'updated_at' => '2021-11-30 10:18:18',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'activity_id' => 3,
                'starts_at' => '2021-11-23 10:00:00',
                'ends_at' => '2021-11-23 15:00:00',
                'fit_selectable' => 1,
                'ticket_type_id' => 3,
                'stock' => 150,
                'purchase_price' => 140.0,
                'sales_price' => 240.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 10:18:06',
                'updated_at' => '2021-11-30 10:18:18',
                'deleted_at' => NULL,
            ),
        ));
    }
}
