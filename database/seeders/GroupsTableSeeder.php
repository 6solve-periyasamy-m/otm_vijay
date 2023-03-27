<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        DB::table('groups')->delete();

        DB::table('groups')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'deleted_at' => '2022-06-29 07:57:54',
                    'created_at' => '2022-06-29 07:57:34',
                    'updated_at' => '2022-06-29 07:57:54',
                ),
            1 =>
                array(
                    'id' => 2,
                    'deleted_at' => NULL,
                    'created_at' => '2022-06-29 07:57:54',
                    'updated_at' => '2022-06-29 07:57:54',
                ),
            2 =>
                array(
                    'id' => 3,
                    'deleted_at' => NULL,
                    'created_at' => '2022-06-29 07:58:32',
                    'updated_at' => '2022-06-29 07:58:32',
                ),
            3 =>
                array(
                    'id' => 4,
                    'deleted_at' => NULL,
                    'created_at' => '2022-06-29 07:59:20',
                    'updated_at' => '2022-06-29 07:59:20',
                ),
            4 =>
                array(
                    'id' => 5,
                    'deleted_at' => NULL,
                    'created_at' => '2022-06-29 07:59:23',
                    'updated_at' => '2022-06-29 07:59:23',
                ),
            5 =>
                array(
                    'id' => 6,
                    'deleted_at' => NULL,
                    'created_at' => '2022-06-29 07:59:25',
                    'updated_at' => '2022-06-29 07:59:25',
                ),
            6 =>
                array(
                    'id' => 7,
                    'deleted_at' => NULL,
                    'created_at' => '2022-06-29 08:21:03',
                    'updated_at' => '2022-06-29 08:21:03',
                ),
            7 =>
                array(
                    'id' => 8,
                    'deleted_at' => NULL,
                    'created_at' => '2022-06-29 08:21:06',
                    'updated_at' => '2022-06-29 08:21:06',
                ),
            8 =>
                array(
                    'id' => 9,
                    'deleted_at' => NULL,
                    'created_at' => '2022-06-29 08:21:07',
                    'updated_at' => '2022-06-29 08:21:07',
                ),
            9 =>
                array(
                    'id' => 10,
                    'deleted_at' => NULL,
                    'created_at' => '2022-06-29 08:21:09',
                    'updated_at' => '2022-06-29 08:21:09',
                ),
            10 =>
                array(
                    'id' => 11,
                    'deleted_at' => NULL,
                    'created_at' => '2022-06-29 08:21:12',
                    'updated_at' => '2022-06-29 08:21:12',
                ),
            11 =>
                array(
                    'id' => 12,
                    'deleted_at' => NULL,
                    'created_at' => '2022-07-19 08:37:50',
                    'updated_at' => '2022-07-19 08:37:50',
                ),
            12 =>
                array(
                    'id' => 13,
                    'deleted_at' => '2022-09-20 10:22:55',
                    'created_at' => '2022-09-14 11:22:24',
                    'updated_at' => '2022-09-20 10:22:55',
                ),
            13 =>
                array(
                    'id' => 14,
                    'deleted_at' => NULL,
                    'created_at' => '2022-09-20 10:22:55',
                    'updated_at' => '2022-09-20 10:22:55',
                ),
            14 =>
                array(
                    'id' => 15,
                    'deleted_at' => NULL,
                    'created_at' => '2022-09-20 10:22:55',
                    'updated_at' => '2022-09-20 10:22:55',
                ),
            15 =>
                array(
                    'id' => 16,
                    'deleted_at' => NULL,
                    'created_at' => '2022-09-20 10:39:17',
                    'updated_at' => '2022-09-20 10:39:17',
                ),
            16 =>
                array(
                    'id' => 17,
                    'deleted_at' => NULL,
                    'created_at' => '2022-09-20 10:39:18',
                    'updated_at' => '2022-09-20 10:39:18',
                ),
            17 =>
                array(
                    'id' => 18,
                    'deleted_at' => NULL,
                    'created_at' => '2022-09-20 10:39:19',
                    'updated_at' => '2022-09-20 10:39:19',
                ),
            18 =>
                array(
                    'id' => 19,
                    'deleted_at' => '2022-09-21 12:09:54',
                    'created_at' => '2022-09-20 19:33:03',
                    'updated_at' => '2022-09-21 12:09:54',
                ),
            19 =>
                array(
                    'id' => 20,
                    'deleted_at' => NULL,
                    'created_at' => '2022-09-21 11:45:18',
                    'updated_at' => '2022-09-21 11:45:18',
                ),
            20 =>
                array(
                    'id' => 21,
                    'deleted_at' => '2022-09-21 12:11:35',
                    'created_at' => '2022-09-21 12:09:54',
                    'updated_at' => '2022-09-21 12:11:35',
                ),
            21 =>
                array(
                    'id' => 22,
                    'deleted_at' => '2022-09-21 12:11:35',
                    'created_at' => '2022-09-21 12:09:54',
                    'updated_at' => '2022-09-21 12:11:35',
                ),
            22 =>
                array(
                    'id' => 23,
                    'deleted_at' => NULL,
                    'created_at' => '2022-09-21 12:11:35',
                    'updated_at' => '2022-09-21 12:11:35',
                ),
            23 =>
                array(
                    'id' => 24,
                    'deleted_at' => NULL,
                    'created_at' => '2022-09-21 12:44:00',
                    'updated_at' => '2022-09-21 12:44:00',
                ),
        ));


    }
}
