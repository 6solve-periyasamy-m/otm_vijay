<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AddressesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('addresses')->delete();
        
        \DB::table('addresses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Hilton London Kensington',
                'address_parent_id' => 2,
                'location_type_id' => 7,
                'address_line_1' => '179-199 Holland Park Ave',
                'address_line_2' => NULL,
                'address_line_3' => NULL,
                'town' => 'Kensington',
                'region' => 'London',
                'country_id' => 66,
                'postcode' => 'W11 4UL',
                'deleted_at' => NULL,
                'created_at' => '2021-11-22 12:36:26',
                'updated_at' => '2021-11-22 12:36:26',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'The Queens Hotel',
                'address_parent_id' => 2,
                'location_type_id' => 7,
                'address_line_1' => 'The Queens Hotel',
                'address_line_2' => 'City Square',
                'address_line_3' => NULL,
                'town' => NULL,
                'region' => 'Leeds',
                'country_id' => 66,
                'postcode' => 'LS1 1PJ',
                'deleted_at' => NULL,
                'created_at' => '2021-11-22 12:43:22',
                'updated_at' => '2021-11-22 12:43:22',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Trip to Leeds City Museum',
                'address_parent_id' => 3,
                'location_type_id' => 6,
                'address_line_1' => 'Leeds City Museum',
                'address_line_2' => 'Millennium Square',
                'address_line_3' => NULL,
                'town' => NULL,
                'region' => 'Leeds',
                'country_id' => 66,
                'postcode' => 'LS2 8BH',
                'deleted_at' => NULL,
                'created_at' => '2021-11-22 12:46:26',
                'updated_at' => '2021-11-22 12:46:26',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'London Heathrow Airport',
                'address_parent_id' => 4,
                'location_type_id' => 1,
                'address_line_1' => 'Heathrow Airport Limited, The Compass Centre',
                'address_line_2' => 'Nelson Road',
                'address_line_3' => NULL,
                'town' => 'Hounslow',
                'region' => 'Middlesex',
                'country_id' => 66,
                'postcode' => 'TW6 2GW',
                'deleted_at' => NULL,
                'created_at' => '2021-11-22 12:50:10',
                'updated_at' => '2021-11-22 12:50:10',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Leeds Bradford Airport',
                'address_parent_id' => 4,
                'location_type_id' => 1,
                'address_line_1' => 'Leeds Bradford Airport',
                'address_line_2' => 'Whitehouse Ln',
                'address_line_3' => NULL,
                'town' => 'Yeadon',
                'region' => 'Leeds',
                'country_id' => 66,
                'postcode' => 'LS19 7TU',
                'deleted_at' => NULL,
                'created_at' => '2021-11-22 12:52:48',
                'updated_at' => '2021-11-22 12:52:48',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Miss Celeste Gateley',
                'address_parent_id' => 1,
                'location_type_id' => NULL,
                'address_line_1' => '65 Lumley Road',
                'address_line_2' => 'Burley',
                'address_line_3' => NULL,
                'town' => 'Leeds',
                'region' => 'West Yorkshire',
                'country_id' => 66,
                'postcode' => 'LS11 7NU',
                'deleted_at' => NULL,
                'created_at' => '2021-11-22 13:50:17',
                'updated_at' => '2021-11-22 13:50:17',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Miss Celeste Gateley',
                'address_parent_id' => 1,
                'location_type_id' => NULL,
                'address_line_1' => '65 Lumley Road',
                'address_line_2' => 'Burley',
                'address_line_3' => NULL,
                'town' => 'Leeds',
                'region' => 'West Yorkshire',
                'country_id' => 66,
                'postcode' => 'LS11 7NU',
                'deleted_at' => NULL,
                'created_at' => '2021-11-22 13:50:17',
                'updated_at' => '2021-11-22 13:50:17',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Birmingham Airport',
                'address_parent_id' => 4,
                'location_type_id' => 1,
                'address_line_1' => 'Birmingham Airport',
                'address_line_2' => NULL,
                'address_line_3' => NULL,
                'town' => 'Birmingham',
                'region' => 'Birmingham',
                'country_id' => 66,
                'postcode' => 'B26 3QJ',
                'deleted_at' => NULL,
                'created_at' => '2021-11-30 09:35:40',
                'updated_at' => '2021-11-30 09:35:40',
            ),
        ));
        
        
    }
}
