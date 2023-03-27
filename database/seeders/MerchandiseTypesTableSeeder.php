<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MerchandiseTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('merchandise_types')->delete();
        
        \DB::table('merchandise_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'T-Shirt',
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:01:16',
                'updated_at' => '2022-09-09 08:01:16',
            ),
        ));
        
        
    }
}