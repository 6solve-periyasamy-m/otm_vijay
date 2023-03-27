<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuoteAccommodationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('quote_accommodations')->delete();
        
        \DB::table('quote_accommodations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'quote_id' => 1,
                'accommodation_inventory_id' => 3,
                'is_template' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '45.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            1 => 
            array (
                'id' => 2,
                'quote_id' => 1,
                'accommodation_inventory_id' => 7,
                'is_template' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '45.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            2 => 
            array (
                'id' => 3,
                'quote_id' => 1,
                'accommodation_inventory_id' => 11,
                'is_template' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '45.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            3 => 
            array (
                'id' => 4,
                'quote_id' => 1,
                'accommodation_inventory_id' => 15,
                'is_template' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '45.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            4 => 
            array (
                'id' => 5,
                'quote_id' => 1,
                'accommodation_inventory_id' => 19,
                'is_template' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '45.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            5 => 
            array (
                'id' => 6,
                'quote_id' => 1,
                'accommodation_inventory_id' => 23,
                'is_template' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '45.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            6 => 
            array (
                'id' => 7,
                'quote_id' => 1,
                'accommodation_inventory_id' => 27,
                'is_template' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '45.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            7 => 
            array (
                'id' => 8,
                'quote_id' => 1,
                'accommodation_inventory_id' => 31,
                'is_template' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '45.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            8 => 
            array (
                'id' => 9,
                'quote_id' => 1,
                'accommodation_inventory_id' => 35,
                'is_template' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '45.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            9 => 
            array (
                'id' => 10,
                'quote_id' => 1,
                'accommodation_inventory_id' => 9,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            10 => 
            array (
                'id' => 11,
                'quote_id' => 1,
                'accommodation_inventory_id' => 5,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            11 => 
            array (
                'id' => 12,
                'quote_id' => 1,
                'accommodation_inventory_id' => 1,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            12 => 
            array (
                'id' => 13,
                'quote_id' => 1,
                'accommodation_inventory_id' => 33,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            13 => 
            array (
                'id' => 14,
                'quote_id' => 1,
                'accommodation_inventory_id' => 29,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            14 => 
            array (
                'id' => 15,
                'quote_id' => 1,
                'accommodation_inventory_id' => 25,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            15 => 
            array (
                'id' => 16,
                'quote_id' => 1,
                'accommodation_inventory_id' => 21,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            16 => 
            array (
                'id' => 17,
                'quote_id' => 1,
                'accommodation_inventory_id' => 17,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            17 => 
            array (
                'id' => 18,
                'quote_id' => 1,
                'accommodation_inventory_id' => 13,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '50.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            18 => 
            array (
                'id' => 19,
                'quote_id' => 1,
                'accommodation_inventory_id' => 37,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '25.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            19 => 
            array (
                'id' => 20,
                'quote_id' => 1,
                'accommodation_inventory_id' => 38,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '25.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            20 => 
            array (
                'id' => 21,
                'quote_id' => 1,
                'accommodation_inventory_id' => 39,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '25.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            21 => 
            array (
                'id' => 22,
                'quote_id' => 1,
                'accommodation_inventory_id' => 40,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '25.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            22 => 
            array (
                'id' => 23,
                'quote_id' => 1,
                'accommodation_inventory_id' => 41,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '25.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            23 => 
            array (
                'id' => 24,
                'quote_id' => 1,
                'accommodation_inventory_id' => 42,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '25.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            24 => 
            array (
                'id' => 25,
                'quote_id' => 1,
                'accommodation_inventory_id' => 43,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '25.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            25 => 
            array (
                'id' => 26,
                'quote_id' => 1,
                'accommodation_inventory_id' => 44,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '25.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            26 => 
            array (
                'id' => 27,
                'quote_id' => 1,
                'accommodation_inventory_id' => 45,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '25.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 09:39:27',
                'updated_at' => '2022-09-14 09:39:27',
            ),
            27 => 
            array (
                'id' => 28,
                'quote_id' => 2,
                'accommodation_inventory_id' => 47,
                'is_template' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '45.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            28 => 
            array (
                'id' => 29,
                'quote_id' => 2,
                'accommodation_inventory_id' => 48,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '40.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            29 => 
            array (
                'id' => 30,
                'quote_id' => 2,
                'accommodation_inventory_id' => 52,
                'is_template' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '80.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            30 => 
            array (
                'id' => 31,
                'quote_id' => 2,
                'accommodation_inventory_id' => 54,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '80.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 10:54:13',
                'updated_at' => '2022-09-14 10:54:13',
            ),
            31 => 
            array (
                'id' => 32,
                'quote_id' => 2,
                'accommodation_inventory_id' => 50,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '60.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-14 14:01:32',
                'updated_at' => '2022-09-14 14:01:32',
            ),
            32 => 
            array (
                'id' => 33,
                'quote_id' => 3,
                'accommodation_inventory_id' => 47,
                'is_template' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '45.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
            33 => 
            array (
                'id' => 34,
                'quote_id' => 3,
                'accommodation_inventory_id' => 48,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '40.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
            34 => 
            array (
                'id' => 35,
                'quote_id' => 3,
                'accommodation_inventory_id' => 52,
                'is_template' => 1,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '80.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
            35 => 
            array (
                'id' => 36,
                'quote_id' => 3,
                'accommodation_inventory_id' => 54,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '80.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
            36 => 
            array (
                'id' => 37,
                'quote_id' => 3,
                'accommodation_inventory_id' => 53,
                'is_template' => 0,
                'tour_component_type' => 'Included',
                'tour_sales_price' => '90.00',
                'deleted_at' => NULL,
                'created_at' => '2022-09-20 10:36:07',
                'updated_at' => '2022-09-20 10:36:07',
            ),
        ));
        
        
    }
}