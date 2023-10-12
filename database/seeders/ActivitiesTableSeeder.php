<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ActivitiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('activities')->delete();
        
        \DB::table('activities')->insert(array (
            0 => 
            array (
                'id' => 1,
                'activity_type_id' => 1,
                'description' => 'A braai down by the beach',
                'image_url' => 'images/activity/activity_2.jpg',
                'address_id' => 3,
                'currency_id' => 30,
                'name' => 'False Bay Braai',
                'internal_notes' => NULL,
                'created_at' => '2022-01-20 13:34:06',
                'updated_at' => '2023-09-21 15:31:20',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'activity_type_id' => 1,
                'description' => 'A tour of the local vineyard',
                'image_url' => 'images/activity/activity_3.jpg',
                'address_id' => 4,
                'currency_id' => 7,
                'name' => 'Vineyards Tour',
                'internal_notes' => NULL,
                'created_at' => '2022-01-20 15:32:09',
                'updated_at' => '2022-01-20 15:32:09',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'activity_type_id' => 1,
                'description' => 'African 14-course menu with live drumming and Mali puppets.',
                'image_url' => 'images/activity/activity_4.jpg',
                'address_id' => 67,
                'currency_id' => 30,
                'name' => 'Gold Restaurant',
                'internal_notes' => NULL,
                'created_at' => '2022-01-21 10:45:10',
                'updated_at' => '2023-09-21 15:33:26',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'activity_type_id' => 1,
                'description' => 'A tour around the town of Soweto',
                'image_url' => 'images/activity/activity_5.webp',
                'address_id' => 68,
                'currency_id' => 30,
                'name' => 'Soweto Tour',
                'internal_notes' => NULL,
                'created_at' => '2022-01-21 11:01:07',
                'updated_at' => '2023-09-21 15:34:14',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'activity_type_id' => 2,
                'description' => 'images/activity/activity_6.jpg',
                'image_url' => NULL,
                'address_id' => 93,
                'currency_id' => 30,
                'name' => 'England v South Africa 1st Test',
                'internal_notes' => NULL,
                'created_at' => '2022-09-14 10:13:11',
                'updated_at' => '2022-09-14 10:13:11',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'activity_type_id' => 2,
                'description' => 'images/activity/activity_6.jpg',
                'image_url' => NULL,
                'address_id' => 94,
                'currency_id' => 30,
                'name' => 'England v South Africa 2nd Test',
                'internal_notes' => NULL,
                'created_at' => '2022-09-14 10:16:01',
                'updated_at' => '2022-09-14 10:16:01',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'activity_type_id' => 3,
                'description' => 'Gorée is a tiny, car-free island off the coast of Dakar, in Senegal. It’s known for its role in the 15th- to 19th-century Atlantic slave trade. On the narrow streets, colonial buildings include the House of Slaves, now a museum',
                'image_url' => 'images/activity/activity_1.webp',
                'address_id' => 104,
                'currency_id' => 154,
                'name' => 'Visit to Gorée Island',
                'internal_notes' => NULL,
                'created_at' => '2022-09-20 19:14:58',
                'updated_at' => '2022-09-20 19:14:58',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'activity_type_id' => 5,
                'description' => 'Royal Ascot’s Windsor Enclosure is perfect for those wishing to be close to the heart of the action. Racegoers can enjoy being the first to view the Royal Procession as the carriages journey up the famous Straight Mile and get close to the rails for each race to experience the thrill of horses thundering past.',
                'image_url' => 'images/activity/activity_7.jpg',
                'address_id' => 109,
                'currency_id' => 83,
                'name' => 'Royal Ascot Windsor Enclosure',
                'internal_notes' => NULL,
                'created_at' => '2022-09-21 08:54:04',
                'updated_at' => '2022-09-21 08:54:04',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'activity_type_id' => 4,
                'description' => 'Take a ride in a hot air balloon above Cape town and out into the countryside',
                'image_url' => 'images/activity/activity_8.jpg',
                'address_id' => 128,
                'currency_id' => 30,
                'name' => 'Hot Air Balloon Ride',
                'internal_notes' => NULL,
                'created_at' => '2023-09-21 14:03:54',
                'updated_at' => '2023-09-21 14:03:54',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'activity_type_id' => 4,
                'description' => 'Guided walks tailored for you. Become engrossed by the landscapes, the heritage, the people and the culture whilst on foot. Come walk with me, come see MY St Helena.',
                'image_url' => NULL,
                'address_id' => 129,
                'currency_id' => 30,
                'name' => 'Historic Walking Tour of Jamestown St Helena',
                'internal_notes' => NULL,
                'created_at' => '2023-09-21 14:06:52',
                'updated_at' => '2023-09-21 14:06:52',
                'deleted_at' => NULL,
                'external_notes' => NULL,
            ),
        ));
        
        
    }
}