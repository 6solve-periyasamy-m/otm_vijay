<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TransportInventoryToursTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('transport_inventory_tours')->delete();
        
        \DB::table('transport_inventory_tours')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tour_id' => 1,
                'transport_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => 50.0,
                'created_at' => '2022-01-21 12:44:34',
                'updated_at' => '2022-01-21 12:44:34',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
