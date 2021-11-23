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
                'name' => 'Single',
                'created_at' => '2021-09-10 09:22:22',
                'updated_at' => '2021-09-10 09:22:22',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Group',
                'created_at' => '2021-09-10 09:22:27',
                'updated_at' => '2021-09-10 09:22:27',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'All Inclusive Single',
                'created_at' => '2021-09-10 09:22:38',
                'updated_at' => '2021-09-10 09:22:38',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'All Inclusive Group',
                'created_at' => '2021-09-10 09:22:46',
                'updated_at' => '2021-09-10 09:22:46',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
