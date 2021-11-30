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
                'starts_at' => '2021-11-23 10:00:00',
                'ends_at' => '2021-11-23 15:00:00',
                'fit_selectable' => 1,
                'ticket_type_id' => 1,
                'stock' => 150,
                'purchase_price' => 0.0,
                'sales_price' => 20.0,
                'notes' => NULL,
                'created_at' => '2021-11-22 12:47:15',
                'updated_at' => '2021-11-22 12:47:15',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'activity_id' => 1,
                'starts_at' => '2021-11-23 10:00:00',
                'ends_at' => '2021-11-23 15:00:00',
                'fit_selectable' => 1,
                'ticket_type_id' => 3,
                'stock' => 150,
                'purchase_price' => 10.0,
                'sales_price' => 40.0,
                'notes' => NULL,
                'created_at' => '2021-11-30 10:18:06',
                'updated_at' => '2021-11-30 10:18:18',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
