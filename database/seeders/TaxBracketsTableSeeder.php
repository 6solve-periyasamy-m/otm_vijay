<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TaxBracketsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tax_brackets')->delete();
        
        \DB::table('tax_brackets')->insert(array (
            0 => 
            array (
                'created_at' => '2024-03-22 13:09:44',
                'description' => 'Default Sales Tax for the UK',
                'id' => 1,
                'name' => 'UK Sales Tax',
                'rate' => '20.000',
                'updated_at' => '2024-03-22 13:09:44',
            ),
        ));
        
        
    }
}
