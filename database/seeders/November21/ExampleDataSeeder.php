<?php

namespace Database\Seeders\November21;

use Illuminate\Database\Seeder;

class ExampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('events')->truncate();
        \DB::table('tours')->truncate();
        \DB::table('airlines')->truncate();
        \DB::table('location_types')->truncate();
        \DB::table('locations')->truncate();
        \DB::table('airports')->truncate();

        \DB::table('events')->insert([
            0 => [
                'id' => '1',
                'name' => 'The World Cup',
                'description' => 'The World Cup attracts fans from all over the world. Witness greatness from great seats.',
                'starts_at' => '2023-03-17',
                'ends_at' => '2023-04-20',
                'booking_url' => 'world-cup',
                'notes' => 'This is an example record'
            ]
        ]);

        \DB::table('tours')->insert([
            0 => [
                'id' => '1',
                'title' => 'The World Cup',
                'event_id' => 1,
                'description' => 'Travel with your friends to the best seats for the money and watch all the games',
                'notes' => 'ATOL Certified tour',
                'base_price_per_person' => '3000',
                'margin' => '500',
                'single_occupancy_surcharge' => '100',
                'stock_control_active' => true,
                'stock' => 200,
                'booking_form_url' => 'example-tour',
                'date_from' => '2023-03-15',
                'date_to' => '2023-04-25',
                'is_active' => true
            ],
            1 => [
                'id' => '2',
                'title' => 'World Cup Specials',
                'event_id' => 1,
                'description' => 'Discounted Travel with your friends to the best seats for the money and watch all the games',
                'notes' => 'ATOL Certified tour',
                'base_price_per_person' => '2000',
                'margin' => '300',
                'single_occupancy_surcharge' => '100',
                'stock_control_active' => true,
                'stock' => 20,
                'booking_form_url' => 'world-cup',
                'date_from' => '2023-03-15',
                'date_to' => '2023-04-20',
                'is_active' => false
            ]
        ]);

        \DB::table('airlines')->insert([
            1 => [
                'id' => '1',
                'name' => 'BA'
            ],
            2 => [
                'id' => '2',
                'name' => 'KLM'
            ],
            3 => [
                'id' => '3',
                'name' => 'EasyJet'
            ]
        ]);

        
        \DB::table('location_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Airport',
                'created_at' => '2021-01-15 15:40:29',
                'updated_at' => '2021-01-15 15:40:29',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Sea Port',
                'created_at' => '2021-01-15 15:40:38',
                'updated_at' => '2021-01-15 15:40:38',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Train Station',
                'created_at' => '2021-01-15 15:40:47',
                'updated_at' => '2021-01-15 15:40:47',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Bus Station',
                'created_at' => '2021-01-15 15:40:53',
                'updated_at' => '2021-01-15 15:40:53',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Accommodation',
                'created_at' => '2021-01-15 15:41:00',
                'updated_at' => '2021-01-15 15:41:16',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Point of Interest',
                'created_at' => '2021-01-15 15:41:27',
                'updated_at' => '2021-01-15 15:41:27',
                'deleted_at' => NULL,
            ),
        ));

        \DB::table('locations')->insert(array (
            0 => 
            array (
                'id' => 1,
                'region_id' => 2,
                'name' => 'London Heathrow Airport',
                'created_at' => '2021-01-15 15:41:56',
                'updated_at' => '2021-01-15 15:41:56',
                'deleted_at' => NULL,
                'location_type_id' => 1,
                'address' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'region_id' => 2,
                'name' => 'London Gatwick Airport',
                'created_at' => '2021-01-15 15:42:08',
                'updated_at' => '2021-01-15 15:42:08',
                'deleted_at' => NULL,
                'location_type_id' => 1,
                'address' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'region_id' => 3,
                'name' => 'Copenhagen Airport',
                'created_at' => '2021-01-15 15:43:21',
                'updated_at' => '2021-01-15 15:43:21',
                'deleted_at' => NULL,
                'location_type_id' => 1,
                'address' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'region_id' => 4,
                'name' => 'Amsterdam Schipol Aiport',
                'created_at' => '2021-01-15 15:45:10',
                'updated_at' => '2021-01-15 15:45:10',
                'deleted_at' => NULL,
                'location_type_id' => 1,
                'address' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'region_id' => 2,
                'name' => 'Premier Inn London Gatwick',
                'created_at' => '2021-01-15 15:53:17',
                'updated_at' => '2021-01-15 15:53:17',
                'deleted_at' => NULL,
                'location_type_id' => 5,
                'address' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'region_id' => 2,
                'name' => 'Hilton London Gatwick',
                'created_at' => '2021-01-15 15:53:32',
                'updated_at' => '2021-01-15 15:53:32',
                'deleted_at' => NULL,
                'location_type_id' => 5,
                'address' => NULL,
            ),
        ));
        
        \DB::table('airports')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Amsterdam Schipol Airport',
                'location_id' => 4,
                'iata_code' => 'AMS',
                'created_at' => '2021-01-15 15:45:40',
                'updated_at' => '2021-01-15 15:45:40',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'London Gatwick Airport',
                'location_id' => 2,
                'iata_code' => 'LGW',
                'created_at' => '2021-01-15 15:45:54',
                'updated_at' => '2021-01-15 15:45:54',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Copenhagen Airport',
                'location_id' => 3,
                'iata_code' => 'CPH',
                'created_at' => '2021-01-15 15:46:06',
                'updated_at' => '2021-01-15 15:46:06',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'London Heathrow Airport',
                'location_id' => 1,
                'iata_code' => 'LHR',
                'created_at' => '2021-01-15 15:46:00',
                'updated_at' => '2021-01-15 15:51:57',
                'deleted_at' => NULL,
            ),
        ));

        \DB::table('flights')->delete();
        
        \DB::table('flights')->insert(array (
            0 => 
            array (
                'id' => 6,
                'airline_id' => 1,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 3,
                'is_domestic' => 0,
                'notes' => NULL,
                'is_archived' => 0,
                'created_at' => '2021-02-04 23:24:30',
                'updated_at' => '2021-02-04 23:24:30',
                'deleted_at' => NULL,
                'currency' => '£',
                'available_after' => NULL,
            ),
            1 => 
            array (
                'id' => 7,
                'airline_id' => 1,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 1,
                'is_domestic' => 0,
                'notes' => NULL,
                'is_archived' => 0,
                'created_at' => '2021-02-21 14:25:59',
                'updated_at' => '2021-02-21 14:25:59',
                'deleted_at' => NULL,
                'currency' => '£',
                'available_after' => NULL,
            ),
            2 => 
            array (
                'id' => 8,
                'airline_id' => 3,
                'departure_airport_id' => 2,
                'arrival_airport_id' => 3,
                'is_domestic' => 0,
                'notes' => NULL,
                'is_archived' => 0,
                'created_at' => '2021-02-22 07:41:52',
                'updated_at' => '2021-02-22 07:41:52',
                'deleted_at' => NULL,
                'currency' => '£',
                'available_after' => NULL,
            ),
        ));       

    }
}
