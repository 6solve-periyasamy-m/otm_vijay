<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TransportsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('transports')->delete();
        
        \DB::table('transports')->insert(array (
            0 => 
            array (
                'id' => 1,
                'transport_type_id' => 1,
                'operator_id' => 1,
                'departure_address_id' => 71,
                'arrival_address_id' => 70,
                'is_domestic' => 1,
                'name' => 'Train to JBR Airport from CPT Airport',
                'image_url' => NULL,
                'description' => 'Train to JBR Airport from CPT Airport',
                'notes' => NULL,
                'currency_id' => 7,
                'created_at' => '2022-01-21 12:42:03',
                'updated_at' => '2022-01-21 12:42:03',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'transport_type_id' => 1,
                'operator_id' => 1,
                'departure_address_id' => 71,
                'arrival_address_id' => 1,
                'is_domestic' => 1,
                'name' => 'Airport Transfer - Cape Town International to ONOMO Foreshore',
                'image_url' => NULL,
                'description' => NULL,
                'notes' => NULL,
                'currency_id' => 83,
                'created_at' => '2022-09-14 10:48:13',
                'updated_at' => '2022-09-14 10:48:13',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'transport_type_id' => 1,
                'operator_id' => 1,
                'departure_address_id' => 1,
                'arrival_address_id' => 71,
                'is_domestic' => 1,
                'name' => 'Airport Transfer - ONOMO Foreshore to Cape Town International',
                'image_url' => NULL,
                'description' => NULL,
                'notes' => NULL,
                'currency_id' => 83,
                'created_at' => '2022-09-14 11:58:16',
                'updated_at' => '2022-09-14 11:58:30',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'transport_type_id' => 2,
                'operator_id' => 2,
                'departure_address_id' => 97,
                'arrival_address_id' => 103,
                'is_domestic' => 0,
                'name' => 'Airport Transfer Banjul - Senegambia',
                'image_url' => NULL,
            'description' => 'Standard Transfer (One Way)',
                'notes' => NULL,
                'currency_id' => 154,
                'created_at' => '2022-09-20 19:05:10',
                'updated_at' => '2022-09-20 19:11:32',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'transport_type_id' => 2,
                'operator_id' => 2,
                'departure_address_id' => 103,
                'arrival_address_id' => 97,
                'is_domestic' => 0,
                'name' => 'Airport Transfer Senegambia - Banjul',
                'image_url' => NULL,
            'description' => 'Standard Transfer (One Way)',
                'notes' => NULL,
                'currency_id' => 154,
                'created_at' => '2022-09-20 19:06:50',
                'updated_at' => '2022-09-20 19:07:05',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'transport_type_id' => 3,
                'operator_id' => 3,
                'departure_address_id' => 110,
                'arrival_address_id' => 109,
                'is_domestic' => 0,
                'name' => 'Luxury Coach taking you to Royal Ascot',
                'image_url' => NULL,
                'description' => NULL,
                'notes' => NULL,
                'currency_id' => 83,
                'created_at' => '2022-09-21 09:01:52',
                'updated_at' => '2022-09-21 09:01:52',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'transport_type_id' => 3,
                'operator_id' => 3,
                'departure_address_id' => 109,
                'arrival_address_id' => 110,
                'is_domestic' => 0,
                'name' => 'Luxury Coach returning to Brixton from Royal Ascot',
                'image_url' => NULL,
                'description' => NULL,
                'notes' => NULL,
                'currency_id' => 83,
                'created_at' => '2022-09-21 09:03:49',
                'updated_at' => '2022-09-21 09:04:04',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}