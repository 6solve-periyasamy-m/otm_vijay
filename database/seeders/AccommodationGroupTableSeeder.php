<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccommodationGroupTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
      DB::table('accommodation_groups')->delete();

      DB::table('accommodation_groups')->insert(
      array (
            array (
            'id' => 1, 'name' => 'Single',
            ),array(
            'id' => 2, 'name' => 'Couple 1', 
            ),array(
            'id' => 3, 'name' => 'Couple 2', 
            ),array(
            'id' => 4, 'name' => 'Couple 3', 
            ),array(
            'id' => 5, 'name' => 'Couple 4', 
            ),array(
            'id' => 6, 'name' => 'Couple 5', 
            ),array(
            'id' => 7, 'name' => 'Couple 6', 
            ),array(
            'id' => 8, 'name' => 'Couple 7', 
            ),array(
            'id' => 9, 'name' => 'Couple 8', 
            ),array(
            'id' => 10, 'name' => 'Couple 9',
            ),array(
            'id' => 11, 'name' => 'Couple 10',
            ),array(
            'id' => 12, 'name' => 'Friends 1',
            ),array(
            'id' => 13, 'name' => 'Friends 2',
            ),array(
            'id' => 14, 'name' => 'Friends 3',
            ),array(
            'id' => 15, 'name' => 'Friends 4',
            ),array(
            'id' => 16, 'name' => 'Friends 5',
            ),array(
            'id' => 17, 'name' => 'Friends 6',
            ),array(
            'id' => 18, 'name' => 'Friends 7',
            ),array(
            'id' => 19, 'name' => 'Friends 8',
            ),array(
            'id' => 20, 'name' => 'Friends 9',
            ),array(
            'id' => 21, 'name' => 'Family 1',
            ),array(
            'id' => 22, 'name' => 'Family 2',
            ),array(
            'id' => 23, 'name' => 'Family 3',
            ),array(
            'id' => 24, 'name' => 'Family 4',
            ),array(
            'id' => 25, 'name' => 'Family 5',
            ),array(
            'id' => 26, 'name' => 'Family 6',
            ),array(
            'id' => 27, 'name' => 'Family 7',
            ),array(
            'id' => 28, 'name' => 'Family 8',
            ),array(
            'id' => 29, 'name' => 'Family 9',
            ),array(
            'id' => 30, 'name' => 'Family 10',
            ),array(
            'id' => 31, 'name' => 'Group 1',
            ),array(
            'id' => 32, 'name' => 'Group 2',
            ),array(
            'id' => 33, 'name' => 'Group 3',
            ),array(
            'id' => 34, 'name' => 'Group 4',
            ),array(
            'id' => 35, 'name' => 'Group 5',
            ),array(
            'id' => 36, 'name' => 'Group 6',
            ),array(
            'id' => 37, 'name' => 'Group 7',
            ),array(
            'id' => 38, 'name' => 'Group 8',
            ),array(
            'id' => 39, 'name' => 'Group 9',
            ),array(
            'id' => 40, 'name' => 'Group 10',
            ),array(
            'id' => 41, 'name' => 'Group 11',
            ),array(
            'id' => 42, 'name' => 'Group 12',
            ),array(
            'id' => 43, 'name' => 'Group 13',
            ),array(
            'id' => 44, 'name' => 'Group 14',
            ),array(
            'id' => 45, 'name' => 'Group 15',
            ),array(
            'id' => 46, 'name' => 'Group 16',
            ),array(
            'id' => 47, 'name' => 'Group 17',
            ),array(
            'id' => 48, 'name' => 'Group 18',
            ),array(
            'id' => 49, 'name' => 'Group 19',
            ),array(
            'id' => 50, 'name' => 'Group 20',
            )
            )
          );
    }
}

