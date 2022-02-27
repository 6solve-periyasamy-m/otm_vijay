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
                'tour_id' => 1,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000100010001XGON',
                'deposit' => 300.0,
                'ordered_on' => '2022-01-23 00:00:00',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
                'token' => NULL,
                'invoice_footer' => 'This is a demo tour, and will not be fulfilled',
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000100020002KQRV',
                'deposit' => 300.0,
                'ordered_on' => '2022-01-04 10:00:00',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
                'token' => NULL,
                'invoice_footer' => 'This is a demo tour, and will not be fulfilled',
            ),
            2 => 
            array (
                'id' => 3,
                'tour_id' => 1,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000100030003PRSE',
                'deposit' => 300.0,
                'ordered_on' => '2022-01-23 00:00:00',
                'cancelled' => 0,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
                'token' => NULL,
                'invoice_footer' => 'This is a demo tour, and will not be fulfilled',
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 1,
                'lead_booker_id' => null,
                'booking_reference' => 'OTM000100040005XTJD',
                'deposit' => 300.0,
                'ordered_on' => '2022-01-21 00:00:00',
                'cancelled' => 1,
                'internal_notes' => NULL,
                'external_notes' => NULL,
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:48:42',
                'deleted_at' => NULL,
                'token' => NULL,
                'invoice_footer' => 'This is a demo tour, and will not be fulfilled',
            ),
        ));

        \DB::table('order_customers')->delete();

        \DB::table('order_customers')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'order_id' => 1,
                    'customer_id' => 1,
                    'tour_cost' => 3498.0,
                    'single_occupancy_surcharge' => 419.67,
                    'travel_insurer' => NULL,
                    'policy_number' => NULL,
                    'created_at' => '2022-01-23 13:02:34',
                    'updated_at' => '2022-01-23 13:02:34',
                    'deleted_at' => NULL,
                ),
            1 =>
                array (
                    'id' => 2,
                    'order_id' => 2,
                    'customer_id' => 4,
                    'tour_cost' => 3498.0,
                    'single_occupancy_surcharge' => 419.67,
                    'travel_insurer' => NULL,
                    'policy_number' => NULL,
                    'created_at' => '2022-01-23 13:21:17',
                    'updated_at' => '2022-01-23 13:21:17',
                    'deleted_at' => NULL,
                ),
            2 =>
                array (
                    'id' => 3,
                    'order_id' => 3,
                    'customer_id' => 3,
                    'tour_cost' => 3498.0,
                    'single_occupancy_surcharge' => 419.67,
                    'travel_insurer' => NULL,
                    'policy_number' => NULL,
                    'created_at' => '2022-01-23 13:25:11',
                    'updated_at' => '2022-01-23 13:25:11',
                    'deleted_at' => NULL,
                ),
            3 =>
                array (
                    'id' => 4,
                    'order_id' => 3,
                    'customer_id' => 18,
                    'tour_cost' => 3498.0,
                    'single_occupancy_surcharge' => 1250.0,
                    'travel_insurer' => 'ASCI',
                    'policy_number' => 'ABDF',
                    'created_at' => '2022-01-23 13:38:49',
                    'updated_at' => '2022-01-23 13:38:49',
                    'deleted_at' => NULL,
                ),
            4 =>
                array (
                    'id' => 5,
                    'order_id' => 4,
                    'customer_id' => 5,
                    'tour_cost' => 3498.0,
                    'single_occupancy_surcharge' => 419.67,
                    'travel_insurer' => NULL,
                    'policy_number' => NULL,
                    'created_at' => '2022-01-23 13:47:50',
                    'updated_at' => '2022-01-23 13:47:50',
                    'deleted_at' => NULL,
                ),
        ));

        $order1 =  Order::find(1);
        $order1->lead_booker_id = 1;
        $order1->save();
        $order2 =  Order::find(2);
        $order2->lead_booker_id = 2;
        $order2->save();
        $order3 =  Order::find(3);
        $order3->lead_booker_id = 3;
        $order3->save();
        $order4 =  Order::find(4);
        $order4->lead_booker_id = 5;
        $order4->save();
        
    }
}
