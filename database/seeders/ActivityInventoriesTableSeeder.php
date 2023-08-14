<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ActivityInventoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('activity_inventories')->delete();
        
        \DB::table('activity_inventories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'activity_id' => 1,
                'starts_at' => '2022-07-30 03:00:00',
                'ends_at' => '2022-07-30 10:00:00',
                'fit_selectable' => 1,
                'ticket_type_id' => 1,
                'stock' => 50,
                'purchase_price' => '10.00',
                'sales_price' => '25.00',
                'internal_notes' => NULL,
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
                'purchase_price' => '25.00',
                'sales_price' => '50.00',
                'internal_notes' => NULL,
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
                'purchase_price' => '50.00',
                'sales_price' => '75.00',
                'internal_notes' => NULL,
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
                'purchase_price' => '15.00',
                'sales_price' => '40.00',
                'internal_notes' => NULL,
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
                'purchase_price' => '25.00',
                'sales_price' => '60.00',
                'internal_notes' => NULL,
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
                'purchase_price' => '25.00',
                'sales_price' => '60.00',
                'internal_notes' => NULL,
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
                'purchase_price' => '25.00',
                'sales_price' => '50.00',
                'internal_notes' => NULL,
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
                'purchase_price' => '25.00',
                'sales_price' => '250.00',
                'internal_notes' => NULL,
                'created_at' => '2022-01-21 11:03:28',
                'updated_at' => '2022-01-21 11:03:28',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'activity_id' => 5,
                'starts_at' => '2023-07-29 09:00:00',
                'ends_at' => '2023-07-30 21:00:00',
                'fit_selectable' => 0,
                'ticket_type_id' => 1,
                'stock' => 50,
                'purchase_price' => '240.00',
                'sales_price' => '350.00',
                'internal_notes' => NULL,
                'created_at' => '2022-09-14 10:14:04',
                'updated_at' => '2022-09-14 10:16:46',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'activity_id' => 6,
                'starts_at' => '2023-07-31 09:00:00',
                'ends_at' => '2023-08-01 21:00:00',
                'fit_selectable' => 0,
                'ticket_type_id' => 1,
                'stock' => 50,
                'purchase_price' => '250.00',
                'sales_price' => '360.00',
                'internal_notes' => NULL,
                'created_at' => '2022-09-14 10:17:11',
                'updated_at' => '2022-09-14 10:17:11',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'activity_id' => 6,
                'starts_at' => '2023-08-01 09:00:00',
                'ends_at' => '2023-08-01 21:00:00',
                'fit_selectable' => 0,
                'ticket_type_id' => 1,
                'stock' => 50,
                'purchase_price' => '250.00',
                'sales_price' => '360.00',
                'internal_notes' => NULL,
                'created_at' => '2022-09-14 12:18:10',
                'updated_at' => '2022-09-14 12:18:24',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'activity_id' => 7,
                'starts_at' => '2022-11-20 08:00:00',
                'ends_at' => '2022-11-20 18:00:00',
                'fit_selectable' => 0,
                'ticket_type_id' => 4,
                'stock' => 10,
                'purchase_price' => '100.00',
                'sales_price' => '150.00',
                'internal_notes' => NULL,
                'created_at' => '2022-09-20 19:16:07',
                'updated_at' => '2022-09-20 19:16:07',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'activity_id' => 8,
                'starts_at' => '2023-06-22 10:30:00',
                'ends_at' => '2023-06-22 20:00:00',
                'fit_selectable' => 0,
                'ticket_type_id' => 4,
                'stock' => 15,
                'purchase_price' => '37.00',
                'sales_price' => '75.00',
                'internal_notes' => NULL,
                'created_at' => '2022-09-21 08:57:14',
                'updated_at' => '2022-09-21 08:57:14',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
