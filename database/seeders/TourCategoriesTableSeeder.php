<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TourCategoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tour_categories')->delete();
        
        \DB::table('tour_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'AGA Demo',
                'created_at' => '2022-09-20 19:40:10',
                'updated_at' => '2022-09-20 19:40:10',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}