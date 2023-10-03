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
                'image_url' => 'images/transport/transport_1.jpg',
                'description' => 'Train to JBR Airport from CPT Airport',
                'internal_notes' => NULL,
                'currency_id' => 7,
                'created_at' => '2022-01-21 12:42:03',
                'updated_at' => '2022-01-21 12:42:03',
                'deleted_at' => NULL,
                'external_notes' => NULL,
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
                'image_url' => 'images/transport/transport_1.jpg',
                'description' => NULL,
                'internal_notes' => NULL,
                'currency_id' => 83,
                'created_at' => '2022-09-14 10:48:13',
                'updated_at' => '2022-09-14 10:48:13',
                'deleted_at' => NULL,
                'external_notes' => NULL,
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
                'image_url' => 'images/transport/transport_1.jpg',
                'description' => NULL,
                'internal_notes' => NULL,
                'currency_id' => 83,
                'created_at' => '2022-09-14 11:58:16',
                'updated_at' => '2022-09-14 11:58:30',
                'deleted_at' => NULL,
                'external_notes' => NULL,
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
                'image_url' => 'images/transport/transport_2.jpg',
            'description' => 'Standard Transfer (One Way)',
                'internal_notes' => NULL,
                'currency_id' => 154,
                'created_at' => '2022-09-20 19:05:10',
                'updated_at' => '2022-09-20 19:11:32',
                'deleted_at' => NULL,
                'external_notes' => NULL,
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
                'image_url' => 'images/transport/transport_2.jpg',
            'description' => 'Standard Transfer (One Way)',
                'internal_notes' => NULL,
                'currency_id' => 154,
                'created_at' => '2022-09-20 19:06:50',
                'updated_at' => '2022-09-20 19:07:05',
                'deleted_at' => NULL,
                'external_notes' => NULL,
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
                'image_url' => 'images/transport/transport_2.jpg',
                'description' => NULL,
                'internal_notes' => NULL,
                'currency_id' => 83,
                'created_at' => '2022-09-21 09:01:52',
                'updated_at' => '2022-09-21 09:01:52',
                'deleted_at' => NULL,
                'external_notes' => NULL,
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
                'image_url' => 'images/transport/transport_2.jpg',
                'description' => NULL,
                'internal_notes' => NULL,
                'currency_id' => 83,
                'created_at' => '2022-09-21 09:03:49',
                'updated_at' => '2022-09-21 09:04:04',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'transport_type_id' => 4,
                'operator_id' => 3,
                'departure_address_id' => 133,
                'arrival_address_id' => 69,
                'is_domestic' => 0,
                'name' => 'Private Transfer from your Home to the Airport',
                'image_url' => NULL,
                'description' => 'Please purchase this Add-On if you require a private transfer from your home address to the airport to begin your Tour Package with us.',
                'internal_notes' => NULL,
                'currency_id' => 83,
                'created_at' => '2023-09-22 12:51:58',
                'updated_at' => '2023-09-22 12:51:58',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'transport_type_id' => 5,
                'operator_id' => 3,
                'departure_address_id' => 70,
                'arrival_address_id' => 1,
                'is_domestic' => 0,
                'name' => 'Coach Transfer from O.R Tambo to the Signature Lux ONOMO Foreshore',
                'image_url' => 'images/transport/transport_4.jpg',
                'description' => 'Basic coach transfer from Airport to hotel',
                'internal_notes' => NULL,
                'currency_id' => 30,
                'created_at' => '2023-09-22 12:57:28',
                'updated_at' => '2023-09-22 12:57:28',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'transport_type_id' => 4,
                'operator_id' => 3,
                'departure_address_id' => 70,
                'arrival_address_id' => 1,
                'is_domestic' => 0,
                'name' => 'Private Taxi Transfer from O.R Tambo to the Signature Lux ONOMO Foreshore',
                'image_url' => 'images/transport/transport_5.jpg',
                'description' => 'Luxury transport from Airport to Hotel',
                'internal_notes' => NULL,
                'currency_id' => 30,
                'created_at' => '2023-09-22 13:11:01',
                'updated_at' => '2023-09-22 13:11:01',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
        ));
        
        
    }
}