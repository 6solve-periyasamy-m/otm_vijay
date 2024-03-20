<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderCachesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_caches')->delete();
        
        \DB::table('order_caches')->insert(array (
            0 => 
            array (
                'cached' => '2024-03-15 13:15:38',
                'commission_amount' => NULL,
                'cost' => '3917.67',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 1,
                'next_payment_amount' => '692.00',
                'next_payment_date' => '2021-08-01',
                'next_payment_remaining' => '692.00',
                'order_id' => 1,
                'status' => -2,
                'total_owed' => '300.00',
                'updated_at' => '2024-03-15 13:15:38',
            ),
            1 => 
            array (
                'cached' => '2024-03-15 13:15:38',
                'commission_amount' => NULL,
                'cost' => '3917.67',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 2,
                'next_payment_amount' => NULL,
                'next_payment_date' => NULL,
                'next_payment_remaining' => NULL,
                'order_id' => 2,
                'status' => 2,
                'total_owed' => '3917.67',
                'updated_at' => '2024-03-15 13:15:38',
            ),
            2 => 
            array (
                'cached' => '2024-03-15 13:15:38',
                'commission_amount' => NULL,
                'cost' => '6996.00',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 3,
                'next_payment_amount' => '692.00',
                'next_payment_date' => '2021-08-01',
                'next_payment_remaining' => '359.00',
                'order_id' => 3,
                'status' => -1,
                'total_owed' => '1625.00',
                'updated_at' => '2024-03-15 13:15:38',
            ),
            3 => 
            array (
                'cached' => '2024-03-15 13:15:38',
                'commission_amount' => NULL,
                'cost' => '3498.00',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 4,
                'next_payment_amount' => '692.00',
                'next_payment_date' => '2021-08-01',
                'next_payment_remaining' => '692.00',
                'order_id' => 4,
                'status' => -3,
                'total_owed' => '0.00',
                'updated_at' => '2024-03-15 13:15:38',
            ),
            4 => 
            array (
                'cached' => '2024-03-15 13:15:38',
                'commission_amount' => NULL,
                'cost' => '11753.01',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 5,
                'next_payment_amount' => '692.00',
                'next_payment_date' => '2021-08-01',
                'next_payment_remaining' => '1976.00',
                'order_id' => 5,
                'status' => 2,
                'total_owed' => '11753.01',
                'updated_at' => '2024-03-15 13:15:38',
            ),
            5 => 
            array (
                'cached' => '2024-03-15 13:15:38',
                'commission_amount' => NULL,
                'cost' => '19588.35',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 6,
                'next_payment_amount' => NULL,
                'next_payment_date' => NULL,
                'next_payment_remaining' => NULL,
                'order_id' => 6,
                'status' => 0,
                'total_owed' => '19588.35',
                'updated_at' => '2024-03-15 13:15:38',
            ),
            6 => 
            array (
                'cached' => '2024-03-15 13:15:38',
                'commission_amount' => NULL,
                'cost' => '7865.34',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 7,
                'next_payment_amount' => '692.00',
                'next_payment_date' => '2022-10-01',
                'next_payment_remaining' => '684.00',
                'order_id' => 7,
                'status' => 2,
                'total_owed' => '7865.34',
                'updated_at' => '2024-03-15 13:15:38',
            ),
            7 => 
            array (
                'cached' => '2024-03-15 13:15:38',
                'commission_amount' => NULL,
                'cost' => '6996.00',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 8,
                'next_payment_amount' => '692.00',
                'next_payment_date' => '2022-10-01',
                'next_payment_remaining' => '2076.00',
                'order_id' => 8,
                'status' => 2,
                'total_owed' => '6996.00',
                'updated_at' => '2024-03-15 13:15:38',
            ),
            8 => 
            array (
                'cached' => '2024-03-15 13:15:39',
                'commission_amount' => NULL,
                'cost' => '2398.00',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 9,
                'next_payment_amount' => '239.80',
                'next_payment_date' => '2022-10-01',
                'next_payment_remaining' => '479.60',
                'order_id' => 9,
                'status' => 2,
                'total_owed' => '2398.00',
                'updated_at' => '2024-03-15 13:15:39',
            ),
            9 => 
            array (
                'cached' => '2024-03-15 13:15:39',
                'commission_amount' => NULL,
                'cost' => '150.00',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 10,
                'next_payment_amount' => '100.00',
                'next_payment_date' => '2023-04-30',
                'next_payment_remaining' => '100.00',
                'order_id' => 10,
                'status' => 2,
                'total_owed' => '150.00',
                'updated_at' => '2024-03-15 13:15:39',
            ),
            10 => 
            array (
                'cached' => '2024-03-15 13:15:39',
                'commission_amount' => NULL,
                'cost' => '150.00',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 11,
                'next_payment_amount' => '100.00',
                'next_payment_date' => '2023-04-30',
                'next_payment_remaining' => '100.00',
                'order_id' => 11,
                'status' => 2,
                'total_owed' => '150.00',
                'updated_at' => '2024-03-15 13:15:39',
            ),
            11 => 
            array (
                'cached' => '2024-03-15 13:15:39',
                'commission_amount' => NULL,
                'cost' => '150.00',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 12,
                'next_payment_amount' => NULL,
                'next_payment_date' => NULL,
                'next_payment_remaining' => NULL,
                'order_id' => 12,
                'status' => 0,
                'total_owed' => '150.00',
                'updated_at' => '2024-03-15 13:15:39',
            ),
            12 => 
            array (
                'cached' => '2024-03-15 13:15:39',
                'commission_amount' => NULL,
                'cost' => '300.00',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 13,
                'next_payment_amount' => '100.00',
                'next_payment_date' => '2023-04-30',
                'next_payment_remaining' => '200.00',
                'order_id' => 13,
                'status' => 2,
                'total_owed' => '300.00',
                'updated_at' => '2024-03-15 13:15:39',
            ),
            13 => 
            array (
                'cached' => '2024-03-15 13:15:39',
                'commission_amount' => NULL,
                'cost' => '1644.00',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 14,
                'next_payment_amount' => '609.20',
                'next_payment_date' => '2022-10-07',
                'next_payment_remaining' => '109.21',
                'order_id' => 14,
                'status' => 2,
                'total_owed' => '1644.00',
                'updated_at' => '2024-03-15 13:15:39',
            ),
            14 => 
            array (
                'cached' => '2024-03-15 13:15:39',
                'commission_amount' => NULL,
                'cost' => '1644.00',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 15,
                'next_payment_amount' => '239.80',
                'next_payment_date' => '2022-10-01',
                'next_payment_remaining' => '239.80',
                'order_id' => 15,
                'status' => 2,
                'total_owed' => '1644.00',
                'updated_at' => '2024-03-15 13:15:39',
            ),
            15 => 
            array (
                'cached' => '2024-03-15 13:15:39',
                'commission_amount' => NULL,
                'cost' => '13992.00',
                'created_at' => '2024-03-15 13:02:03',
                'id' => 16,
                'next_payment_amount' => '1049.40',
                'next_payment_date' => '2024-01-10',
                'next_payment_remaining' => '3895.60',
                'order_id' => 16,
                'status' => 2,
                'total_owed' => '13992.00',
                'updated_at' => '2024-03-15 13:15:39',
            ),
        ));
        
        
    }
}