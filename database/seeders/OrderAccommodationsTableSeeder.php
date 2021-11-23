<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderAccommodationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_accommodations')->delete();
        
        \DB::table('order_accommodations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_customer_id' => 1,
                'accommodation_inventory_tour_id' => 2,
                'share_with_user_id' => NULL,
                'created_at' => '2021-11-22 13:50:51',
                'updated_at' => '2021-11-22 13:50:51',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
