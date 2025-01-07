<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payments')->delete();
        
        \DB::table('payments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_id' => 1,
                'payment_method_id' => 1,
                'payer_id' => 1,
                'amount' => '300.00',
                'paid_on' => '2022-01-23 01:00:00',
                'deleted_at' => NULL,
                'created_at' => '2022-01-23 13:18:05',
                'updated_at' => '2022-01-23 13:18:05',
            ),
            1 => 
            array (
                'id' => 2,
                'order_id' => 2,
                'payment_method_id' => 3,
                'payer_id' => 2,
                'amount' => '3498.00',
                'paid_on' => '2022-01-23 00:00:00',
                'deleted_at' => NULL,
                'created_at' => '2022-01-23 13:21:35',
                'updated_at' => '2022-01-23 13:21:35',
            ),
            2 => 
            array (
                'id' => 6,
                'order_id' => 1,
                'payment_method_id' => 4,
                'payer_id' => 1,
                'amount' => '1025.00',
                'paid_on' => '2022-01-10 20:22:00',
                'deleted_at' => NULL,
                'created_at' => '2022-01-23 13:45:08',
                'updated_at' => '2022-07-19 12:32:36',
            ),
            3 => 
            array (
                'id' => 7,
                'order_id' => 4,
                'payment_method_id' => 1,
                'payer_id' => 5,
                'amount' => '300.00',
                'paid_on' => '2022-01-10 00:00:00',
                'deleted_at' => NULL,
                'created_at' => '2022-01-23 13:48:14',
                'updated_at' => '2022-01-23 13:48:14',
            ),
            4 => 
            array (
                'id' => 8,
                'order_id' => 4,
                'payment_method_id' => 1,
                'payer_id' => 5,
                'amount' => '-300.00',
                'paid_on' => '2022-01-12 10:00:00',
                'deleted_at' => NULL,
                'created_at' => '2022-01-23 13:48:39',
                'updated_at' => '2022-01-23 13:48:39',
            ),
            5 => 
            array (
                'id' => 9,
                'order_id' => 5,
                'payment_method_id' => 3,
                'payer_id' => 5,
                'amount' => '1000.00',
                'paid_on' => '2022-05-31 14:30:00',
                'deleted_at' => NULL,
                'created_at' => '2022-06-29 07:59:48',
                'updated_at' => '2022-06-29 07:59:48',
            ),
            6 => 
            array (
                'id' => 10,
                'order_id' => 6,
                'payment_method_id' => 6,
                'payer_id' => 13,
                'amount' => '19588.35',
                'paid_on' => '2022-02-15 10:00:00',
                'deleted_at' => NULL,
                'created_at' => '2022-06-29 08:22:15',
                'updated_at' => '2022-06-29 08:22:32',
            ),
            7 => 
            array (
                'id' => 11,
                'order_id' => 1,
                'payment_method_id' => 1,
                'payer_id' => 1,
                'amount' => '-1025.00',
                'paid_on' => '2022-07-01 10:00:00',
                'deleted_at' => NULL,
                'created_at' => '2022-07-19 12:34:11',
                'updated_at' => '2022-07-19 12:34:11',
            ),
            8 => 
            array (
                'id' => 12,
                'order_id' => 3,
                'payment_method_id' => 5,
                'payer_id' => 3,
                'amount' => '600.00',
                'paid_on' => '2022-04-04 10:00:00',
                'deleted_at' => NULL,
                'created_at' => '2022-07-19 12:48:16',
                'updated_at' => '2022-07-19 12:48:16',
            ),
            9 => 
            array (
                'id' => 13,
                'order_id' => 3,
                'payment_method_id' => 4,
                'payer_id' => 3,
                'amount' => '1025.00',
                'paid_on' => '2022-05-05 10:00:00',
                'deleted_at' => NULL,
                'created_at' => '2022-07-19 12:48:38',
                'updated_at' => '2022-07-19 12:48:38',
            ),
            10 => 
            array (
                'id' => 14,
                'order_id' => 7,
                'payment_method_id' => 1,
                'payer_id' => 13,
                'amount' => '600.00',
                'paid_on' => '2022-09-14 11:22:22',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 11:22:24',
                'updated_at' => '2022-09-14 11:22:24',
            ),
            11 => 
            array (
                'id' => 15,
                'order_id' => 7,
                'payment_method_id' => 4,
                'payer_id' => 13,
                'amount' => '500.00',
                'paid_on' => '2022-09-14 11:00:00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 12:50:21',
                'updated_at' => '2022-09-14 12:50:21',
            ),
            12 => 
            array (
                'id' => 16,
                'order_id' => 7,
                'payment_method_id' => 5,
                'payer_id' => 13,
                'amount' => '200.00',
                'paid_on' => '2022-09-20 10:00:00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:23:48',
                'updated_at' => '2022-09-20 10:23:48',
            ),
            13 => 
            array (
                'id' => 17,
                'order_id' => 9,
                'payment_method_id' => 1,
                'payer_id' => NULL,
                'amount' => '700.00',
                'paid_on' => '2022-09-20 19:33:01',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 19:33:04',
                'updated_at' => '2022-09-20 19:33:04',
            ),
            14 => 
            array (
                'id' => 18,
                'order_id' => 10,
                'payment_method_id' => 3,
                'payer_id' => NULL,
                'amount' => '50.00',
                'paid_on' => '2022-09-01 03:16:00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-21 09:24:43',
                'updated_at' => '2022-09-21 09:24:43',
            ),
            15 => 
            array (
                'id' => 19,
                'order_id' => 11,
                'payment_method_id' => 1,
                'payer_id' => NULL,
                'amount' => '50.00',
                'paid_on' => '2022-08-13 13:30:00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-21 09:28:37',
                'updated_at' => '2022-09-21 09:28:37',
            ),
            16 => 
            array (
                'id' => 20,
                'order_id' => 12,
                'payment_method_id' => 1,
                'payer_id' => NULL,
                'amount' => '150.00',
                'paid_on' => '2022-09-21 10:15:00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-21 09:30:59',
                'updated_at' => '2022-09-21 09:30:59',
            ),
            17 => 
            array (
                'id' => 21,
                'order_id' => 13,
                'payment_method_id' => 1,
                'payer_id' => NULL,
                'amount' => '100.00',
                'paid_on' => '2022-09-21 10:28:00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-21 09:32:45',
                'updated_at' => '2022-09-21 09:32:45',
            ),
            18 => 
            array (
                'id' => 22,
                'order_id' => 14,
                'payment_method_id' => 1,
                'payer_id' => NULL,
                'amount' => '350.00',
                'paid_on' => '2022-09-21 11:45:16',
                'deleted_at' => NULL,
                'created_at' => '2022-09-21 11:45:18',
                'updated_at' => '2022-09-21 11:45:18',
            ),
            19 => 
            array (
                'id' => 23,
                'order_id' => 14,
                'payment_method_id' => 1,
                'payer_id' => NULL,
                'amount' => '239.80',
                'paid_on' => '2022-09-21 11:54:29',
                'deleted_at' => NULL,
                'created_at' => '2022-09-21 11:54:30',
                'updated_at' => '2022-09-21 11:54:30',
            ),
            20 => 
            array (
                'id' => 24,
                'order_id' => 14,
                'payment_method_id' => 5,
                'payer_id' => NULL,
                'amount' => '500.00',
                'paid_on' => '2022-09-21 12:30:00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-21 11:56:40',
                'updated_at' => '2022-09-21 11:56:40',
            ),
            21 => 
            array (
                'id' => 25,
                'order_id' => 16,
                'payment_method_id' => 1,
                'payer_id' => 1,
                'amount' => '5000.00',
                'paid_on' => '2023-09-22 14:20:00',
                'deleted_at' => NULL,
                'created_at' => '2023-09-22 13:21:03',
                'updated_at' => '2023-09-22 13:21:21',
            ),
        ));
        
        
    }
}
