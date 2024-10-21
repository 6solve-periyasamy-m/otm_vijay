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
                'id' => 1,
                'order_id' => 1,
                'status' => -2,
                'commission_amount' => NULL,
                'cost' => '3917.67',
                'total_owed' => '300.00',
                'next_payment_date' => '2021-08-01',
                'next_payment_amount' => '692.00',
                'next_payment_remaining' => '692.00',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '445.00',
            ),
            1 => 
            array (
                'id' => 2,
                'order_id' => 2,
                'status' => 2,
                'commission_amount' => NULL,
                'cost' => '3917.67',
                'total_owed' => '3917.67',
                'next_payment_date' => '2022-06-30',
                'next_payment_amount' => '457.67',
                'next_payment_remaining' => '419.68',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '400.00',
            ),
            2 => 
            array (
                'id' => 3,
                'order_id' => 3,
                'status' => -1,
                'commission_amount' => NULL,
                'cost' => '6996.00',
                'total_owed' => '1625.00',
                'next_payment_date' => '2021-08-01',
                'next_payment_amount' => '692.00',
                'next_payment_remaining' => '359.00',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '890.00',
            ),
            3 => 
            array (
                'id' => 4,
                'order_id' => 4,
                'status' => -3,
                'commission_amount' => NULL,
                'cost' => '3498.00',
                'total_owed' => '0.00',
                'next_payment_date' => '2021-08-01',
                'next_payment_amount' => '692.00',
                'next_payment_remaining' => '692.00',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '175.00',
            ),
            4 => 
            array (
                'id' => 5,
                'order_id' => 5,
                'status' => 2,
                'commission_amount' => NULL,
                'cost' => '11753.01',
                'total_owed' => '11753.01',
                'next_payment_date' => '2021-08-01',
                'next_payment_amount' => '692.00',
                'next_payment_remaining' => '1976.00',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '1200.00',
            ),
            5 => 
            array (
                'id' => 6,
                'order_id' => 6,
                'status' => 0,
                'commission_amount' => NULL,
                'cost' => '19588.35',
                'total_owed' => '19588.35',
                'next_payment_date' => NULL,
                'next_payment_amount' => NULL,
                'next_payment_remaining' => NULL,
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '2000.00',
            ),
            6 => 
            array (
                'id' => 7,
                'order_id' => 7,
                'status' => 2,
                'commission_amount' => NULL,
                'cost' => '7865.34',
                'total_owed' => '7865.34',
                'next_payment_date' => '2022-10-01',
                'next_payment_amount' => '692.00',
                'next_payment_remaining' => '684.00',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '3616.00',
            ),
            7 => 
            array (
                'id' => 8,
                'order_id' => 8,
                'status' => 2,
                'commission_amount' => NULL,
                'cost' => '6996.00',
                'total_owed' => '6996.00',
                'next_payment_date' => '2022-10-01',
                'next_payment_amount' => '692.00',
                'next_payment_remaining' => '2076.00',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '5394.00',
            ),
            8 => 
            array (
                'id' => 9,
                'order_id' => 9,
                'status' => 2,
                'commission_amount' => NULL,
                'cost' => '2398.00',
                'total_owed' => '2398.00',
                'next_payment_date' => '2022-10-01',
                'next_payment_amount' => '239.80',
                'next_payment_remaining' => '479.60',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '1625.00',
            ),
            9 => 
            array (
                'id' => 10,
                'order_id' => 10,
                'status' => 2,
                'commission_amount' => NULL,
                'cost' => '150.00',
                'total_owed' => '150.00',
                'next_payment_date' => '2023-04-30',
                'next_payment_amount' => '100.00',
                'next_payment_remaining' => '100.00',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '67.00',
            ),
            10 => 
            array (
                'id' => 11,
                'order_id' => 11,
                'status' => 2,
                'commission_amount' => NULL,
                'cost' => '150.00',
                'total_owed' => '150.00',
                'next_payment_date' => '2023-04-30',
                'next_payment_amount' => '100.00',
                'next_payment_remaining' => '100.00',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '67.00',
            ),
            11 => 
            array (
                'id' => 12,
                'order_id' => 12,
                'status' => 0,
                'commission_amount' => NULL,
                'cost' => '150.00',
                'total_owed' => '150.00',
                'next_payment_date' => NULL,
                'next_payment_amount' => NULL,
                'next_payment_remaining' => NULL,
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '67.00',
            ),
            12 => 
            array (
                'id' => 13,
                'order_id' => 13,
                'status' => 2,
                'commission_amount' => NULL,
                'cost' => '300.00',
                'total_owed' => '300.00',
                'next_payment_date' => '2023-04-30',
                'next_payment_amount' => '100.00',
                'next_payment_remaining' => '200.00',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '134.00',
            ),
            13 => 
            array (
                'id' => 14,
                'order_id' => 14,
                'status' => 2,
                'commission_amount' => NULL,
                'cost' => '1644.00',
                'total_owed' => '1644.00',
                'next_payment_date' => '2022-10-07',
                'next_payment_amount' => '1054.20',
                'next_payment_remaining' => '554.21',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '910.00',
            ),
            14 => 
            array (
                'id' => 15,
                'order_id' => 15,
                'status' => 2,
                'commission_amount' => NULL,
                'cost' => '1644.00',
                'total_owed' => '1644.00',
                'next_payment_date' => '2022-10-01',
                'next_payment_amount' => '239.80',
                'next_payment_remaining' => '239.80',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '910.00',
            ),
            15 => 
            array (
                'id' => 16,
                'order_id' => 16,
                'status' => 2,
                'commission_amount' => NULL,
                'cost' => '13992.00',
                'total_owed' => '13992.00',
                'next_payment_date' => '2024-01-10',
                'next_payment_amount' => '1049.40',
                'next_payment_remaining' => '3895.60',
                'cached' => '2024-10-21 13:07:46',
                'created_at' => '2024-03-15 13:02:03',
                'updated_at' => '2024-10-21 13:07:46',
                'cost_to_company' => '6520.00',
            ),
        ));
        
        
    }
}