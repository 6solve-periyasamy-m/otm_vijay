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
                'name' => 'Commemorative T-Shirt',
                'tour_component_type' => 'Add-on',
                'tour_id' => 1,
                'image_url' => NULL,
                'stock' => 200,
                'purchase_price' => 10.0,
                'tour_sales_price' => 35.0,
                'notes' => NULL,
                'created_at' => '2022-01-21 12:14:13',
                'updated_at' => '2022-01-21 12:14:13',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
