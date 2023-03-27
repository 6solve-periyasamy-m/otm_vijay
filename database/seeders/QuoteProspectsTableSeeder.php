<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuoteProspectsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('quote_prospects')->delete();
        
        \DB::table('quote_prospects')->insert(array (
            0 => 
            array (
                'id' => 1,
                'customer_id' => 3,
                'paying' => 1,
                'travelling' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            1 => 
            array (
                'id' => 2,
                'customer_id' => 13,
                'paying' => 1,
                'travelling' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            2 => 
            array (
                'id' => 3,
                'customer_id' => 16,
                'paying' => 1,
                'travelling' => 1,
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
        ));
        
        
    }
}