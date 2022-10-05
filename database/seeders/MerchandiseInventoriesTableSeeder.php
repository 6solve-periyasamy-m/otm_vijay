<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MerchandiseInventoriesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('merchandise_inventories')->delete();
        
        \DB::table('merchandise_inventories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'merchandise_id' => 1,
                'variant_id' => 1,
                'merchandise_size_id' => 1,
                'image_url' => 'images/shirt_white.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '8.00',
                'sales_price' => '15.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:03:14',
                'updated_at' => '2022-09-09 08:03:14',
            ),
            1 => 
            array (
                'id' => 2,
                'merchandise_id' => 1,
                'variant_id' => 1,
                'merchandise_size_id' => 2,
                'image_url' => 'images/shirt_white.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '8.00',
                'sales_price' => '15.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:03:19',
                'updated_at' => '2022-09-09 08:03:24',
            ),
            2 => 
            array (
                'id' => 3,
                'merchandise_id' => 1,
                'variant_id' => 1,
                'merchandise_size_id' => 3,
                'image_url' => 'images/shirt_white.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '8.00',
                'sales_price' => '15.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:03:33',
                'updated_at' => '2022-09-09 08:03:37',
            ),
            3 => 
            array (
                'id' => 4,
                'merchandise_id' => 1,
                'variant_id' => 1,
                'merchandise_size_id' => 4,
                'image_url' => 'images/shirt_white.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '8.00',
                'sales_price' => '15.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:03:39',
                'updated_at' => '2022-09-09 08:03:42',
            ),
            4 => 
            array (
                'id' => 5,
                'merchandise_id' => 1,
                'variant_id' => 2,
                'merchandise_size_id' => 1,
                'image_url' => 'images/shirt_red.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '7.00',
                'sales_price' => '15.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:04:06',
                'updated_at' => '2022-09-09 08:04:06',
            ),
            5 => 
            array (
                'id' => 6,
                'merchandise_id' => 1,
                'variant_id' => 2,
                'merchandise_size_id' => 2,
                'image_url' => 'images/shirt_red.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '7.00',
                'sales_price' => '15.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:04:09',
                'updated_at' => '2022-09-09 08:04:12',
            ),
            6 => 
            array (
                'id' => 7,
                'merchandise_id' => 1,
                'variant_id' => 2,
                'merchandise_size_id' => 3,
                'image_url' => 'images/shirt_red.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '7.00',
                'sales_price' => '15.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:04:14',
                'updated_at' => '2022-09-09 08:04:18',
            ),
            7 => 
            array (
                'id' => 8,
                'merchandise_id' => 1,
                'variant_id' => 3,
                'merchandise_size_id' => 1,
                'image_url' => 'images/shirt_green.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '7.00',
                'sales_price' => '15.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:04:36',
                'updated_at' => '2022-09-09 08:04:36',
            ),
            8 => 
            array (
                'id' => 9,
                'merchandise_id' => 1,
                'variant_id' => 3,
                'merchandise_size_id' => 2,
                'image_url' => 'images/shirt_green.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '7.00',
                'sales_price' => '15.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:04:46',
                'updated_at' => '2022-09-09 08:04:49',
            ),
            9 => 
            array (
                'id' => 10,
                'merchandise_id' => 1,
                'variant_id' => 3,
                'merchandise_size_id' => 3,
                'image_url' => 'images/shirt_green.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '7.00',
                'sales_price' => '15.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:04:51',
                'updated_at' => '2022-09-09 08:04:55',
            ),
            10 => 
            array (
                'id' => 11,
                'merchandise_id' => 1,
                'variant_id' => 3,
                'merchandise_size_id' => 4,
                'image_url' => 'images/shirt_green.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '9.00',
                'sales_price' => '16.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:04:58',
                'updated_at' => '2022-09-09 08:05:05',
            ),
            11 => 
            array (
                'id' => 12,
                'merchandise_id' => 1,
                'variant_id' => 2,
                'merchandise_size_id' => 4,
                'image_url' => 'images/shirt_red.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '8.00',
                'sales_price' => '17.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:05:20',
                'updated_at' => '2022-09-09 08:05:29',
            ),
            12 => 
            array (
                'id' => 13,
                'merchandise_id' => 1,
                'variant_id' => 2,
                'merchandise_size_id' => 5,
                'image_url' => 'images/shirt_red.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '10.00',
                'sales_price' => '22.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:05:32',
                'updated_at' => '2022-09-09 08:05:39',
            ),
            13 => 
            array (
                'id' => 14,
                'merchandise_id' => 1,
                'variant_id' => 4,
                'merchandise_size_id' => 1,
                'image_url' => 'images/shirt_yellow.jpg',
                'fit_selectable' => 0,
                'stock' => 25,
                'purchase_price' => '6.00',
                'sales_price' => '14.00',
                'notes' => NULL,
                'deleted_at' => NULL,
                'created_at' => '2022-09-09 08:06:04',
                'updated_at' => '2022-09-09 08:06:04',
            ),
        ));
        
        
    }
}
