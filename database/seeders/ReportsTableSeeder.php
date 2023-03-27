<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('reports')->delete();
        
        \DB::table('reports')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Transport',
                'description' => 'Transport',
                'parent' => 'transport',
                'fields' => '["name","operator","departs_at","arrives_at","total_stock","used_stock","tour_name","tour_component_type"]',
                'created_at' => '2022-09-20 10:43:34',
                'updated_at' => '2022-09-20 10:43:34',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}