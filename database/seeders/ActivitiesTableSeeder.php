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
                'description' => 'Leeds City Museum, originally established in 1819, reopened in 2008 in Leeds, West Yorkshire, England. It is housed in the former Mechanics\' Institute built by Cuthbert Brodrick, in Cookridge Street. It is one of nine sites in the Leeds Museums & Galleries group. Admission to the museum is free of charge.',
                'address_id' => 3,
                'currency_id' => 46,
                'name' => 'Trip to Leeds City Museum',
                'notes' => NULL,
                'created_at' => '2021-11-22 12:46:26',
                'updated_at' => '2021-11-22 12:46:26',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
