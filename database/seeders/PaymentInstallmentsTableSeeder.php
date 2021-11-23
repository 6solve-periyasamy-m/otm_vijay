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
                'amount' => 100.0,
                'due_on' => '2021-11-18',
                'created_at' => '2021-11-22 13:30:24',
                'updated_at' => '2021-11-22 13:32:22',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
