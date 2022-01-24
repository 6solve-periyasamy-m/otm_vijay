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
                'amount' => -398.0,
                'reason' => 'Discount for Repeat Customer',
                'date' => '2022-01-23',
                'deleted_at' => NULL,
                'created_at' => '2022-01-23 13:20:06',
                'updated_at' => '2022-01-23 13:20:52',
            ),
        ));
        
        
    }
}
