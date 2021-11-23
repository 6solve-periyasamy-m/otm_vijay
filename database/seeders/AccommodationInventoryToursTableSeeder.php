<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccommodationInventoryToursTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('accommodation_inventory_tours')->delete();
        
        \DB::table('accommodation_inventory_tours')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tour_id' => 1,
                'accommodation_inventory_id' => 3,
                'tour_sales_price' => 250.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2021-11-22 13:32:55',
                'updated_at' => '2021-11-22 13:36:11',
                'deleted_at' => '2021-11-22 13:36:11',
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'accommodation_inventory_id' => 3,
                'tour_sales_price' => 250.0,
                'tour_component_type' => 'Included',
                'booking_policy' => 'overbook',
                'created_at' => '2021-11-22 13:45:41',
                'updated_at' => '2021-11-22 13:45:41',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
