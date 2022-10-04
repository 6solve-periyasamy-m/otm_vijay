<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentInstallmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_installments')->delete();
        
        \DB::table('payment_installments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'tour_id' => 1,
                'is_percentage' => 0,
                'amount' => '692.00',
                'due_on' => '2021-08-01',
                'created_at' => '2022-01-20 11:33:49',
                'updated_at' => '2022-01-20 11:33:49',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'is_percentage' => 0,
                'amount' => '692.00',
                'due_on' => '2021-11-01',
                'created_at' => '2022-01-20 11:35:27',
                'updated_at' => '2022-01-20 11:35:27',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'tour_id' => 1,
                'is_percentage' => 0,
                'amount' => '692.00',
                'due_on' => '2022-02-01',
                'created_at' => '2022-01-20 11:35:47',
                'updated_at' => '2022-01-20 11:35:47',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 1,
                'is_percentage' => 0,
                'amount' => '1084.00',
                'due_on' => '2022-05-10',
                'created_at' => '2022-01-20 11:36:13',
                'updated_at' => '2022-01-20 11:36:13',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'tour_id' => 2,
                'is_percentage' => 0,
                'amount' => '692.00',
                'due_on' => '2022-10-01',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:54:23',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'tour_id' => 2,
                'is_percentage' => 0,
                'amount' => '692.00',
                'due_on' => '2022-12-01',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:54:37',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'tour_id' => 2,
                'is_percentage' => 0,
                'amount' => '692.00',
                'due_on' => '2023-02-01',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:54:48',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'tour_id' => 2,
                'is_percentage' => 0,
                'amount' => '1084.00',
                'due_on' => '2023-05-10',
                'created_at' => '2022-09-14 09:49:13',
                'updated_at' => '2022-09-14 09:55:00',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'tour_id' => 4,
                'is_percentage' => 0,
                'amount' => '692.00',
                'due_on' => '2022-10-01',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'tour_id' => 4,
                'is_percentage' => 0,
                'amount' => '692.00',
                'due_on' => '2022-12-01',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'tour_id' => 4,
                'is_percentage' => 0,
                'amount' => '692.00',
                'due_on' => '2023-02-01',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'tour_id' => 4,
                'is_percentage' => 0,
                'amount' => '1084.00',
                'due_on' => '2023-05-10',
                'created_at' => '2022-09-20 10:39:17',
                'updated_at' => '2022-09-20 10:39:17',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'tour_id' => 5,
                'is_percentage' => 1,
                'amount' => '20.00',
                'due_on' => '2022-10-01',
                'created_at' => '2022-09-20 18:41:03',
                'updated_at' => '2022-09-20 18:41:03',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'tour_id' => 6,
                'is_percentage' => 1,
                'amount' => '35.00',
                'due_on' => '2022-12-01',
                'created_at' => '2022-09-21 08:47:18',
                'updated_at' => '2022-09-21 08:59:03',
                'deleted_at' => '2022-09-21 08:59:03',
            ),
            14 => 
            array (
                'id' => 15,
                'tour_id' => 6,
                'is_percentage' => 1,
                'amount' => '35.00',
                'due_on' => '2023-02-28',
                'created_at' => '2022-09-21 08:47:41',
                'updated_at' => '2022-09-21 08:59:09',
                'deleted_at' => '2022-09-21 08:59:09',
            ),
        ));
        
        
    }
}