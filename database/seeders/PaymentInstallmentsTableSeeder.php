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
                'amount' => 692.0,
                'due_on' => '2021-08-01',
                'created_at' => '2022-01-20 11:33:49',
                'updated_at' => '2022-01-20 11:33:49',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'tour_id' => 1,
                'amount' => 692.0,
                'due_on' => '2021-11-01',
                'created_at' => '2022-01-20 11:35:27',
                'updated_at' => '2022-01-20 11:35:27',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'tour_id' => 1,
                'amount' => 692.0,
                'due_on' => '2022-02-01',
                'created_at' => '2022-01-20 11:35:47',
                'updated_at' => '2022-01-20 11:35:47',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'tour_id' => 1,
                'amount' => 1084.0,
                'due_on' => '2022-05-10',
                'created_at' => '2022-01-20 11:36:13',
                'updated_at' => '2022-01-20 11:36:13',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
