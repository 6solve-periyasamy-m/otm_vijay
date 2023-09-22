<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TicketTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ticket_types')->delete();
        
        \DB::table('ticket_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Basic',
                'created_at' => '2022-01-20 13:34:25',
                'updated_at' => '2022-01-20 13:34:25',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'All-you-can-eat',
                'created_at' => '2022-01-20 13:35:54',
                'updated_at' => '2022-01-20 13:35:54',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Backstage Access',
                'created_at' => '2022-01-20 13:37:18',
                'updated_at' => '2022-01-20 13:37:18',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Full Access',
                'created_at' => '2022-01-20 15:36:10',
                'updated_at' => '2022-01-20 15:36:10',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'After-Party Access',
                'created_at' => '2022-01-20 15:53:16',
                'updated_at' => '2022-01-20 15:53:16',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Full Access - Lunch Included',
                'created_at' => '2022-07-05 11:17:41',
                'updated_at' => '2022-07-05 11:17:41',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Full Tasting Menu - Food Only',
                'created_at' => '2022-07-05 11:33:28',
                'updated_at' => '2022-07-05 11:33:28',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Full Tasting Menu - Including Wine Pairings',
                'created_at' => '2022-07-05 11:34:26',
                'updated_at' => '2022-07-05 11:34:26',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}