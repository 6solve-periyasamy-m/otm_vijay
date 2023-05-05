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
                'description' => 'Signature Lux Hotel by ONOMO Foreshore',
                'audit_date' => '2022-01-20',
                'image_url' => 'images/accommodation/accommodation_2.webp',
                'currency_id' => 30,
                'address_id' => 1,
                'created_at' => '2022-01-20 11:47:54',
                'updated_at' => '2022-09-14 09:57:09',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Signature Lux Hotel by ONOMO, Sandton',
                'description' => 'In a bustling area a minute’s walk from shops at Nelson Mandela Square, this relaxed hotel is a 3-minute walk from Sandton train station and a 4-minute walk from the Sandton Convention Centre.',
                'audit_date' => '2022-01-20',
                'image_url' => 'images/accommodation/accommodation_3.jpg',
                'currency_id' => 7,
                'address_id' => 2,
                'created_at' => '2022-01-20 12:44:50',
                'updated_at' => '2022-01-20 12:44:50',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Long Street Backpackers',
                'description' => 'Set on iconic and lively Long Street, this simple hostel with a colourful facade is 7 minutes\' walk from the Iziko South African Museum and 4 km from the Table Mountain Aerial Cableway. Pared-down private rooms and dorms have personal lockers and shared bathrooms. Amenities include a lively bar, a communal kitchen and a TV room, plus 3 communal balconies overlooking the street. There\'s also a pool table, and a garden courtyard with barbecue facilities and seating.',
                'audit_date' => '2022-02-09',
                'image_url' => 'images/accommodation/accommodation_4.jpg',
                'currency_id' => 32,
                'address_id' => 76,
                'created_at' => '2022-02-09 09:30:21',
                'updated_at' => '2022-02-09 09:30:21',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Senegambia Hotel',
                'description' => 'Client Advice: Senegambia Hotel: We recommend booking on bed and breakfast as it is likely half board and other dining packages may be cheaper locally; Please note the hotel only accepts payment by cash',
                'audit_date' => '2022-09-20',
                'image_url' => 'images/accommodation/hotel_1.jpg',
                'currency_id' => 154,
                'address_id' => 103,
                'created_at' => '2022-09-20 18:51:56',
                'updated_at' => '2022-09-20 18:51:56',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
