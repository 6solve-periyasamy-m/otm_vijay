<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('orders')->delete();
        
        \DB::table('orders')->insert(array (
            0 => 
            array (
                'id' => 1,
                'quote_id' => NULL,
                'tour_id' => 1,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000100010001LSAF',
                'ordered_on' => '2021-11-22 11:11:00',
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'created_at' => '2021-11-22 13:50:51',
                'updated_at' => '2021-11-22 13:50:51',
                'deleted_at' => NULL,
                'token' => '0201231',
            ),
        ));

        \DB::table('order_customers')->delete();

        \DB::table('order_customers')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'order_id' => 1,
                    'customer_id' => 1,
                    'tour_cost' => 250.0,
                    'single_occupancy_surcharge' => 100.0,
                    'travel_insurer' => NULL,
                    'policy_number' => NULL,
                    'created_at' => '2021-11-22 13:50:51',
                    'updated_at' => '2021-11-22 13:50:51',
                    'deleted_at' => NULL,
                ),
        ));
        $order = Order::find(1);
        $order->lead_booker_id = 1;
        $order->save();
        
        
    }
}
