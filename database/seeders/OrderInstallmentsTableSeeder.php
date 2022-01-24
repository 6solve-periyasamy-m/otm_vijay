<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderInstallmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_installments')->delete();
        
        \DB::table('order_installments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_id' => 1,
                'amount' => 692.0,
                'due_on' => '2021-08-01',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'order_id' => 1,
                'amount' => 692.0,
                'due_on' => '2021-11-01',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'order_id' => 1,
                'amount' => 692.0,
                'due_on' => '2022-02-01',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'order_id' => 1,
                'amount' => 1084.0,
                'due_on' => '2022-05-10',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'order_id' => 2,
                'amount' => 692.0,
                'due_on' => '2021-08-01',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'order_id' => 2,
                'amount' => 692.0,
                'due_on' => '2021-11-01',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'order_id' => 2,
                'amount' => 692.0,
                'due_on' => '2022-02-01',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'order_id' => 2,
                'amount' => 1084.0,
                'due_on' => '2022-05-10',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'order_id' => 3,
                'amount' => 692.0,
                'due_on' => '2021-08-01',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'order_id' => 3,
                'amount' => 692.0,
                'due_on' => '2021-11-01',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'order_id' => 3,
                'amount' => 692.0,
                'due_on' => '2022-02-01',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'order_id' => 3,
                'amount' => 1084.0,
                'due_on' => '2022-05-10',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'order_id' => 4,
                'amount' => 692.0,
                'due_on' => '2021-08-01',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'order_id' => 4,
                'amount' => 692.0,
                'due_on' => '2021-11-01',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'order_id' => 4,
                'amount' => 692.0,
                'due_on' => '2022-02-01',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'order_id' => 4,
                'amount' => 1084.0,
                'due_on' => '2022-05-10',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
