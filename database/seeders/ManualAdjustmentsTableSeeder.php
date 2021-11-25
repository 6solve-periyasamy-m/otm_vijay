<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ManualAdjustmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('manual_adjustments')->delete();
        
        \DB::table('manual_adjustments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_id' => 1,
                'amount' => -25.0,
                'reason' => '10% Discount with coupon: 1DJRTL',
                'date' => '2021-11-22',
                'deleted_at' => '2021-11-22 14:33:20',
                'created_at' => '2021-11-22 13:57:36',
                'updated_at' => '2021-11-22 14:33:20',
            ),
            1 => 
            array (
                'id' => 2,
                'order_id' => 1,
                'amount' => -25.0,
                'reason' => '10% Discount with coupon: 1DJRTL',
                'date' => '2021-11-22',
                'deleted_at' => NULL,
                'created_at' => '2021-11-22 14:34:02',
                'updated_at' => '2021-11-22 14:34:02',
            ),
        ));
        
        
    }
}
