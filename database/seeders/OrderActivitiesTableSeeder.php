<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderActivitiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_activities')->delete();
        
        \DB::table('order_activities')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_customer_id' => 1,
                'activity_inventory_tour_id' => 2,
                'created_at' => '2021-11-22 13:50:51',
                'updated_at' => '2021-11-22 13:50:51',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
