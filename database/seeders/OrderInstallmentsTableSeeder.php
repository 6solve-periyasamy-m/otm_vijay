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
                'amount' => '692.00',
                'due_on' => '2021-08-01',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'order_id' => 1,
                'amount' => '692.00',
                'due_on' => '2021-11-01',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'order_id' => 1,
                'amount' => '692.00',
                'due_on' => '2022-02-01',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'order_id' => 1,
                'amount' => '1084.00',
                'due_on' => '2022-05-10',
                'created_at' => '2022-01-23 13:02:34',
                'updated_at' => '2022-01-23 13:02:34',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'order_id' => 2,
                'amount' => '692.00',
                'due_on' => '2021-08-01',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'order_id' => 2,
                'amount' => '692.00',
                'due_on' => '2021-11-01',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'order_id' => 2,
                'amount' => '692.00',
                'due_on' => '2022-02-01',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'order_id' => 2,
                'amount' => '1084.00',
                'due_on' => '2022-05-10',
                'created_at' => '2022-01-23 13:21:17',
                'updated_at' => '2022-01-23 13:21:17',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'order_id' => 3,
                'amount' => '692.00',
                'due_on' => '2021-08-01',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'order_id' => 3,
                'amount' => '692.00',
                'due_on' => '2021-11-01',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'order_id' => 3,
                'amount' => '692.00',
                'due_on' => '2022-02-01',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'order_id' => 3,
                'amount' => '1084.00',
                'due_on' => '2022-05-10',
                'created_at' => '2022-01-23 13:25:11',
                'updated_at' => '2022-01-23 13:25:11',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'order_id' => 4,
                'amount' => '692.00',
                'due_on' => '2021-08-01',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'order_id' => 4,
                'amount' => '692.00',
                'due_on' => '2021-11-01',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'order_id' => 4,
                'amount' => '692.00',
                'due_on' => '2022-02-01',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'order_id' => 4,
                'amount' => '1084.00',
                'due_on' => '2022-05-10',
                'created_at' => '2022-01-23 13:47:50',
                'updated_at' => '2022-01-23 13:47:50',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'order_id' => 5,
                'amount' => '692.00',
                'due_on' => '2021-08-01',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'order_id' => 5,
                'amount' => '692.00',
                'due_on' => '2021-11-01',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'order_id' => 5,
                'amount' => '692.00',
                'due_on' => '2022-02-01',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'order_id' => 5,
                'amount' => '1084.00',
                'due_on' => '2022-05-10',
                'created_at' => '2022-06-29 07:59:20',
                'updated_at' => '2022-06-29 07:59:20',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'order_id' => 6,
                'amount' => '692.00',
                'due_on' => '2021-08-01',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'order_id' => 6,
                'amount' => '692.00',
                'due_on' => '2021-11-01',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'order_id' => 6,
                'amount' => '692.00',
                'due_on' => '2022-02-01',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'order_id' => 6,
                'amount' => '1084.00',
                'due_on' => '2022-05-10',
                'created_at' => '2022-06-29 08:21:03',
                'updated_at' => '2022-06-29 08:21:03',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'order_id' => 7,
                'amount' => '692.00',
                'due_on' => '2022-10-01',
                'created_at' => '2022-09-14 11:22:24',
                'updated_at' => '2022-09-14 11:22:24',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'order_id' => 7,
                'amount' => '692.00',
                'due_on' => '2022-12-01',
                'created_at' => '2022-09-14 11:22:24',
                'updated_at' => '2022-09-14 11:22:24',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'order_id' => 7,
                'amount' => '692.00',
                'due_on' => '2023-02-01',
                'created_at' => '2022-09-14 11:22:24',
                'updated_at' => '2022-09-14 11:22:24',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'order_id' => 7,
                'amount' => '1084.00',
                'due_on' => '2023-05-10',
                'created_at' => '2022-09-14 11:22:24',
                'updated_at' => '2022-09-14 11:22:24',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'order_id' => 8,
                'amount' => '692.00',
                'due_on' => '2022-10-01',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'order_id' => 8,
                'amount' => '692.00',
                'due_on' => '2022-12-01',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'order_id' => 8,
                'amount' => '692.00',
                'due_on' => '2023-02-01',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'order_id' => 8,
                'amount' => '1084.00',
                'due_on' => '2023-05-10',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'order_id' => 9,
                'amount' => '239.80',
                'due_on' => '2022-10-01',
                'created_at' => '2022-09-20 19:33:03',
                'updated_at' => '2022-09-20 19:33:03',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'order_id' => 14,
                'amount' => '239.80',
                'due_on' => '2022-10-01',
                'created_at' => '2022-09-21 11:45:18',
                'updated_at' => '2022-09-21 11:45:18',
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'order_id' => 15,
                'amount' => '239.80',
                'due_on' => '2022-10-01',
                'created_at' => '2022-09-21 12:44:00',
                'updated_at' => '2022-09-21 12:44:00',
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'order_id' => 16,
                'amount' => '874.50',
                'due_on' => '2023-12-01',
                'created_at' => '2023-09-22 13:22:32',
                'updated_at' => '2023-09-22 13:22:32',
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'order_id' => 16,
                'amount' => '1049.40',
                'due_on' => '2024-01-10',
                'created_at' => '2023-09-22 13:22:32',
                'updated_at' => '2023-09-22 13:22:32',
                'deleted_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'order_id' => 16,
                'amount' => '874.50',
                'due_on' => '2024-03-01',
                'created_at' => '2023-09-22 13:22:32',
                'updated_at' => '2023-09-22 13:22:32',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}