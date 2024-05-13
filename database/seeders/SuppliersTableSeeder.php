<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SuppliersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('suppliers')->delete();
        
        \DB::table('suppliers')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Suppliers R Us',
                'telephone' => '01274812393',
                'email' => 'admin@suppliers-r-us.com',
                'website' => 'https://suppliers-r-us.com',
                'address_id' => 128,
                'currency_id' => 1,
                'agreed_exchange' => '0.95',
                'notes' => NULL,
                'created_at' => '2023-08-25 10:40:38',
                'updated_at' => '2023-08-25 10:40:38',
            ),
        ));
        
        
    }
}