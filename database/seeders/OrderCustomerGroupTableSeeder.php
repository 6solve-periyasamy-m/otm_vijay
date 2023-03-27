<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderCustomerGroupTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_customer_group')->delete();
        
        \DB::table('order_customer_group')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_customer_id' => 1,
                'group_id' => 1,
                'deleted_at' => '2022-06-29 07:57:54',
            ),
            1 => 
            array (
                'id' => 2,
                'order_customer_id' => 1,
                'group_id' => 2,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'order_customer_id' => 3,
                'group_id' => 3,
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'order_customer_id' => 4,
                'group_id' => 3,
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'order_customer_id' => 6,
                'group_id' => 4,
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'order_customer_id' => 7,
                'group_id' => 5,
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'order_customer_id' => 8,
                'group_id' => 6,
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'order_customer_id' => 9,
                'group_id' => 7,
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'order_customer_id' => 10,
                'group_id' => 8,
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'order_customer_id' => 11,
                'group_id' => 9,
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'order_customer_id' => 12,
                'group_id' => 10,
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'order_customer_id' => 13,
                'group_id' => 11,
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'order_customer_id' => 2,
                'group_id' => 12,
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'order_customer_id' => 14,
                'group_id' => 13,
                'deleted_at' => '2022-09-20 10:22:55',
            ),
            14 => 
            array (
                'id' => 15,
                'order_customer_id' => 15,
                'group_id' => 13,
                'deleted_at' => '2022-09-20 10:22:55',
            ),
            15 => 
            array (
                'id' => 16,
                'order_customer_id' => 14,
                'group_id' => 14,
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'order_customer_id' => 15,
                'group_id' => 15,
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'order_customer_id' => 16,
                'group_id' => 16,
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'order_customer_id' => 17,
                'group_id' => 17,
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'order_customer_id' => 18,
                'group_id' => 18,
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'order_customer_id' => 19,
                'group_id' => 19,
                'deleted_at' => '2022-09-21 12:09:54',
            ),
            21 => 
            array (
                'id' => 22,
                'order_customer_id' => 20,
                'group_id' => 19,
                'deleted_at' => '2022-09-21 12:09:54',
            ),
            22 => 
            array (
                'id' => 23,
                'order_customer_id' => 26,
                'group_id' => 20,
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'order_customer_id' => 20,
                'group_id' => 21,
                'deleted_at' => '2022-09-21 12:11:35',
            ),
            24 => 
            array (
                'id' => 25,
                'order_customer_id' => 19,
                'group_id' => 22,
                'deleted_at' => '2022-09-21 12:11:35',
            ),
            25 => 
            array (
                'id' => 26,
                'order_customer_id' => 19,
                'group_id' => 23,
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'order_customer_id' => 20,
                'group_id' => 23,
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'order_customer_id' => 27,
                'group_id' => 24,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}