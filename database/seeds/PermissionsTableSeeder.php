<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'key' => 'browse_admin',
                'table_name' => NULL,
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            1 => 
            array (
                'id' => 2,
                'key' => 'browse_bread',
                'table_name' => NULL,
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            2 => 
            array (
                'id' => 3,
                'key' => 'browse_database',
                'table_name' => NULL,
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            3 => 
            array (
                'id' => 4,
                'key' => 'browse_media',
                'table_name' => NULL,
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            4 => 
            array (
                'id' => 5,
                'key' => 'browse_compass',
                'table_name' => NULL,
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            5 => 
            array (
                'id' => 6,
                'key' => 'browse_menus',
                'table_name' => 'menus',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            6 => 
            array (
                'id' => 7,
                'key' => 'read_menus',
                'table_name' => 'menus',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            7 => 
            array (
                'id' => 8,
                'key' => 'edit_menus',
                'table_name' => 'menus',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            8 => 
            array (
                'id' => 9,
                'key' => 'add_menus',
                'table_name' => 'menus',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            9 => 
            array (
                'id' => 10,
                'key' => 'delete_menus',
                'table_name' => 'menus',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            10 => 
            array (
                'id' => 11,
                'key' => 'browse_roles',
                'table_name' => 'roles',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            11 => 
            array (
                'id' => 12,
                'key' => 'read_roles',
                'table_name' => 'roles',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            12 => 
            array (
                'id' => 13,
                'key' => 'edit_roles',
                'table_name' => 'roles',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            13 => 
            array (
                'id' => 14,
                'key' => 'add_roles',
                'table_name' => 'roles',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            14 => 
            array (
                'id' => 15,
                'key' => 'delete_roles',
                'table_name' => 'roles',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            15 => 
            array (
                'id' => 16,
                'key' => 'browse_users',
                'table_name' => 'users',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            16 => 
            array (
                'id' => 17,
                'key' => 'read_users',
                'table_name' => 'users',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            17 => 
            array (
                'id' => 18,
                'key' => 'edit_users',
                'table_name' => 'users',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            18 => 
            array (
                'id' => 19,
                'key' => 'add_users',
                'table_name' => 'users',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            19 => 
            array (
                'id' => 20,
                'key' => 'delete_users',
                'table_name' => 'users',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            20 => 
            array (
                'id' => 21,
                'key' => 'browse_settings',
                'table_name' => 'settings',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            21 => 
            array (
                'id' => 22,
                'key' => 'read_settings',
                'table_name' => 'settings',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            22 => 
            array (
                'id' => 23,
                'key' => 'edit_settings',
                'table_name' => 'settings',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            23 => 
            array (
                'id' => 24,
                'key' => 'add_settings',
                'table_name' => 'settings',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            24 => 
            array (
                'id' => 25,
                'key' => 'delete_settings',
                'table_name' => 'settings',
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            25 => 
            array (
                'id' => 26,
                'key' => 'browse_hooks',
                'table_name' => NULL,
                'created_at' => '2020-10-13 13:08:28',
                'updated_at' => '2020-10-13 13:08:28',
            ),
            26 => 
            array (
                'id' => 27,
                'key' => 'browse_accommodations',
                'table_name' => 'accommodations',
                'created_at' => '2020-10-13 13:24:21',
                'updated_at' => '2020-10-13 13:24:21',
            ),
            27 => 
            array (
                'id' => 28,
                'key' => 'read_accommodations',
                'table_name' => 'accommodations',
                'created_at' => '2020-10-13 13:24:21',
                'updated_at' => '2020-10-13 13:24:21',
            ),
            28 => 
            array (
                'id' => 29,
                'key' => 'edit_accommodations',
                'table_name' => 'accommodations',
                'created_at' => '2020-10-13 13:24:21',
                'updated_at' => '2020-10-13 13:24:21',
            ),
            29 => 
            array (
                'id' => 30,
                'key' => 'add_accommodations',
                'table_name' => 'accommodations',
                'created_at' => '2020-10-13 13:24:21',
                'updated_at' => '2020-10-13 13:24:21',
            ),
            30 => 
            array (
                'id' => 31,
                'key' => 'delete_accommodations',
                'table_name' => 'accommodations',
                'created_at' => '2020-10-13 13:24:21',
                'updated_at' => '2020-10-13 13:24:21',
            ),
        ));
        
        
    }
}