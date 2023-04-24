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
                'currency_id' => 7,
                'name' => 'False Bay Braai',
                'notes' => NULL,
                'created_at' => '2022-01-20 13:34:06',
                'updated_at' => '2022-01-20 13:34:06',
                'deleted_at' => NULL,
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
                'notes' => NULL,
                'created_at' => '2022-01-20 15:32:09',
                'updated_at' => '2022-01-20 15:32:09',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'activity_type_id' => 1,
                'description' => 'African 14-course menu with live drumming and Mali puppets.',
                'image_url' => 'images/activity/activity_4.jpg',
                'address_id' => 67,
                'currency_id' => 7,
                'name' => 'Gold Restaurant',
                'notes' => NULL,
                'created_at' => '2022-01-21 10:45:10',
                'updated_at' => '2022-01-21 10:45:10',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'activity_type_id' => 1,
                'description' => 'A tour around the town of Soweto',
                'image_url' => 'images/activity/activity_5.webp',
                'address_id' => 68,
                'currency_id' => 7,
                'name' => 'Soweto Tour',
                'notes' => NULL,
                'created_at' => '2022-01-21 11:01:07',
                'updated_at' => '2022-01-21 11:01:07',
                'deleted_at' => NULL,
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
                'notes' => NULL,
                'created_at' => '2022-09-14 10:13:11',
                'updated_at' => '2022-09-14 10:13:11',
                'deleted_at' => NULL,
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
                'notes' => NULL,
                'created_at' => '2022-09-14 10:16:01',
                'updated_at' => '2022-09-14 10:16:01',
                'deleted_at' => NULL,
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
                'notes' => NULL,
                'created_at' => '2022-09-20 19:14:58',
                'updated_at' => '2022-09-20 19:14:58',
                'deleted_at' => NULL,
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
                'notes' => NULL,
                'created_at' => '2022-09-21 08:54:04',
                'updated_at' => '2022-09-21 08:54:04',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
