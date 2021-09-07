<?php

use Illuminate\Database\Seeder;

class PaymentMethodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payment_methods')->delete();
        
        \DB::table('payment_methods')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Stripe',
                'deleted_at' => NULL,
                'created_at' => '2021-09-07 09:34:25',
                'updated_at' => '2021-09-07 09:34:25',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Cash',
                'deleted_at' => NULL,
                'created_at' => '2021-09-07 09:34:30',
                'updated_at' => '2021-09-07 09:34:30',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Card over Phone',
                'deleted_at' => NULL,
                'created_at' => '2021-09-07 09:34:51',
                'updated_at' => '2021-09-07 09:34:51',
            ),
        ));
        
        
    }
}