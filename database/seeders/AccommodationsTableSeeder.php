<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccommodationsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('accommodations')->delete();
        
        \DB::table('accommodations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Signature Lux Hotel by ONOMO Foreshore',
                'description' => 'A 7-minute walk from the Cape Town International Convention Centre, this hip hotel is 3 km from the upmarket V&A Waterfront, offering dining, shopping and scenic views. It\'s 18 km from Cape Town International Airport.',
                'audit_date' => '2022-01-20',
                'image_url' => NULL,
                'currency_id' => 7,
                'address_id' => 1,
                'created_at' => '2022-01-20 11:47:54',
                'updated_at' => '2022-01-20 11:47:54',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Signature Lux Hotel by ONOMO, Sandton',
                'description' => 'In a bustling area a minute’s walk from shops at Nelson Mandela Square, this relaxed hotel is a 3-minute walk from Sandton train station and a 4-minute walk from the Sandton Convention Centre.',
                'audit_date' => '2022-01-20',
                'image_url' => NULL,
                'currency_id' => 7,
                'address_id' => 2,
                'created_at' => '2022-01-20 12:44:50',
                'updated_at' => '2022-01-20 12:44:50',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
