<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MerchandisesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('merchandises')->delete();
        
        \DB::table('merchandises')->insert(array (
            0 => 
            array (
                'id' => 1,
                'merchandise_type_id' => 1,
                'name' => 'Women\'s Fit T',
                'image_url' => 'images/merchandise/shirt_white.jpg',
                'notes' => NULL,
                'created_at' => '2022-09-09 08:01:46',
                'updated_at' => '2022-09-09 08:01:46',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
