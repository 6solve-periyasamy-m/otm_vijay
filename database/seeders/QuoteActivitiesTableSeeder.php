<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuoteActivitiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('quote_activities')->delete();
        
        \DB::table('quote_activities')->insert(array (
            0 => 
            array (
                'id' => 1,
                'quote_id' => 1,
                'activity_inventory_id' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '25.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            1 => 
            array (
                'id' => 2,
                'quote_id' => 1,
                'activity_inventory_id' => 7,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            2 => 
            array (
                'id' => 3,
                'quote_id' => 1,
                'activity_inventory_id' => 8,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '250.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            3 => 
            array (
                'id' => 4,
                'quote_id' => 1,
                'activity_inventory_id' => 4,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '40.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            4 => 
            array (
                'id' => 5,
                'quote_id' => 2,
                'activity_inventory_id' => 9,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '350.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            5 => 
            array (
                'id' => 6,
                'quote_id' => 2,
                'activity_inventory_id' => 10,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '360.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            6 => 
            array (
                'id' => 7,
                'quote_id' => 3,
                'activity_inventory_id' => 9,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '350.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
            7 => 
            array (
                'id' => 8,
                'quote_id' => 3,
                'activity_inventory_id' => 10,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '360.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
        ));
        
        
    }
}