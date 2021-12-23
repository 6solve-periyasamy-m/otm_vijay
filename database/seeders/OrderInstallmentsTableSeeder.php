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
                'amount' => 100.0,
                'due_on' => '2021-11-18',
                'created_at' => '2021-12-23 23:36:11',
                'updated_at' => '2021-12-23 23:36:11',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}