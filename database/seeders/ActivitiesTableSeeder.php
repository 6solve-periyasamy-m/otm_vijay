<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivitiesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        DB::table('activities')->delete();
        DB::table('activities')->insert(array (
            0 => 
            array (
                'id' => 1,
                'activity_type_id' => 1,
                'description' => 'A braai down by the beach',
                'image_url' => NULL,
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
                'image_url' => NULL,
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
                'image_url' => NULL,
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
                'image_url' => NULL,
                'address_id' => 68,
                'currency_id' => 7,
                'name' => 'Soweto Tour',
                'notes' => NULL,
                'created_at' => '2022-01-21 11:01:07',
                'updated_at' => '2022-01-21 11:01:07',
                'deleted_at' => NULL,
            ),
            array (
                'id' => 2,
                'activity_type_id' => 4,
                'description' => 'Leeds City Tour',
                'address_id' => 2,
                'currency_id' => 46,
                'name' => 'City Tour',
                'notes' => NULL,
                'created_at' => '2021-11-22 12:46:26',
                'updated_at' => '2021-11-22 12:46:26',
                'deleted_at' => NULL,
            ),
            array (
                'id' => 3,
                'activity_type_id' => 2,
                'description' => 'Black Tie Dinner',
                'address_id' => 4,
                'currency_id' => 46,
                'name' => 'Leeds City Museum Black Tie Dinner',
                'notes' => NULL,
                'created_at' => '2021-11-22 12:46:26',
                'updated_at' => '2021-11-22 12:46:26',
                'deleted_at' => NULL,
            ),
        ));
    }
}
