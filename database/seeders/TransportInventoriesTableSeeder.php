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
                'purchase_price' => 25.0,
                'sales_price' => 50.0,
                'notes' => NULL,
                'arrival_time_confirmed' => 1,
                'departure_time_confirmed' => 1,
                'created_at' => '2022-01-21 12:42:53',
                'updated_at' => '2022-01-21 12:42:53',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
