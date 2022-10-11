<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuotePricePointsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('quote_price_points')->delete();
        
        \DB::table('quote_price_points')->insert(array (
            0 => 
            array (
                'id' => 1,
                'quote_id' => 1,
                'quantity' => 1,
                'price_per_person' => '3498.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            1 => 
            array (
                'id' => 2,
                'quote_id' => 2,
                'quantity' => 1,
                'price_per_person' => '3498.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            2 => 
            array (
                'id' => 3,
                'quote_id' => 2,
                'quantity' => 3,
                'price_per_person' => '3400.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 11:00:11',
                'updated_at' => '2022-09-14 11:00:11',
            ),
            3 => 
            array (
                'id' => 4,
                'quote_id' => 2,
                'quantity' => 5,
                'price_per_person' => '3300.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 11:00:24',
                'updated_at' => '2022-09-14 11:00:45',
            ),
            4 => 
            array (
                'id' => 5,
                'quote_id' => 3,
                'quantity' => 1,
                'price_per_person' => '3498.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
            5 => 
            array (
                'id' => 6,
                'quote_id' => 3,
                'quantity' => 3,
                'price_per_person' => '3200.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:38',
                'updated_at' => '2022-09-20 10:36:38',
            ),
        ));
        
        
    }
}