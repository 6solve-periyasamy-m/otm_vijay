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
                'tour_sales_price' => NULL,
                'created_at' => '2021-11-22 13:33:15',
                'updated_at' => '2021-11-22 13:40:21',
                'deleted_at' => '2021-11-22 13:40:21',
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'transport_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => NULL,
                'created_at' => '2021-11-22 13:46:09',
                'updated_at' => '2021-11-22 13:46:09',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}