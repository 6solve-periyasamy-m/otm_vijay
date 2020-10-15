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
            31 => 
            array (
                'id' => 32,
                'key' => 'browse_accommodation_inventory',
                'table_name' => 'accommodation_inventory',
                'created_at' => '2020-10-15 09:18:14',
                'updated_at' => '2020-10-15 09:18:14',
            ),
            32 => 
            array (
                'id' => 33,
                'key' => 'read_accommodation_inventory',
                'table_name' => 'accommodation_inventory',
                'created_at' => '2020-10-15 09:18:14',
                'updated_at' => '2020-10-15 09:18:14',
            ),
            33 => 
            array (
                'id' => 34,
                'key' => 'edit_accommodation_inventory',
                'table_name' => 'accommodation_inventory',
                'created_at' => '2020-10-15 09:18:14',
                'updated_at' => '2020-10-15 09:18:14',
            ),
            34 => 
            array (
                'id' => 35,
                'key' => 'add_accommodation_inventory',
                'table_name' => 'accommodation_inventory',
                'created_at' => '2020-10-15 09:18:14',
                'updated_at' => '2020-10-15 09:18:14',
            ),
            35 => 
            array (
                'id' => 36,
                'key' => 'delete_accommodation_inventory',
                'table_name' => 'accommodation_inventory',
                'created_at' => '2020-10-15 09:18:14',
                'updated_at' => '2020-10-15 09:18:14',
            ),
            36 => 
            array (
                'id' => 37,
                'key' => 'browse_accommodation_inventories',
                'table_name' => 'accommodation_inventories',
                'created_at' => '2020-10-15 09:21:00',
                'updated_at' => '2020-10-15 09:21:00',
            ),
            37 => 
            array (
                'id' => 38,
                'key' => 'read_accommodation_inventories',
                'table_name' => 'accommodation_inventories',
                'created_at' => '2020-10-15 09:21:00',
                'updated_at' => '2020-10-15 09:21:00',
            ),
            38 => 
            array (
                'id' => 39,
                'key' => 'edit_accommodation_inventories',
                'table_name' => 'accommodation_inventories',
                'created_at' => '2020-10-15 09:21:00',
                'updated_at' => '2020-10-15 09:21:00',
            ),
            39 => 
            array (
                'id' => 40,
                'key' => 'add_accommodation_inventories',
                'table_name' => 'accommodation_inventories',
                'created_at' => '2020-10-15 09:21:00',
                'updated_at' => '2020-10-15 09:21:00',
            ),
            40 => 
            array (
                'id' => 41,
                'key' => 'delete_accommodation_inventories',
                'table_name' => 'accommodation_inventories',
                'created_at' => '2020-10-15 09:21:00',
                'updated_at' => '2020-10-15 09:21:00',
            ),
            41 => 
            array (
                'id' => 42,
                'key' => 'browse_board_types',
                'table_name' => 'board_types',
                'created_at' => '2020-10-15 10:03:54',
                'updated_at' => '2020-10-15 10:03:54',
            ),
            42 => 
            array (
                'id' => 43,
                'key' => 'read_board_types',
                'table_name' => 'board_types',
                'created_at' => '2020-10-15 10:03:54',
                'updated_at' => '2020-10-15 10:03:54',
            ),
            43 => 
            array (
                'id' => 44,
                'key' => 'edit_board_types',
                'table_name' => 'board_types',
                'created_at' => '2020-10-15 10:03:54',
                'updated_at' => '2020-10-15 10:03:54',
            ),
            44 => 
            array (
                'id' => 45,
                'key' => 'add_board_types',
                'table_name' => 'board_types',
                'created_at' => '2020-10-15 10:03:54',
                'updated_at' => '2020-10-15 10:03:54',
            ),
            45 => 
            array (
                'id' => 46,
                'key' => 'delete_board_types',
                'table_name' => 'board_types',
                'created_at' => '2020-10-15 10:03:54',
                'updated_at' => '2020-10-15 10:03:54',
            ),
            46 => 
            array (
                'id' => 47,
                'key' => 'browse_room_types',
                'table_name' => 'room_types',
                'created_at' => '2020-10-15 11:16:35',
                'updated_at' => '2020-10-15 11:16:35',
            ),
            47 => 
            array (
                'id' => 48,
                'key' => 'read_room_types',
                'table_name' => 'room_types',
                'created_at' => '2020-10-15 11:16:35',
                'updated_at' => '2020-10-15 11:16:35',
            ),
            48 => 
            array (
                'id' => 49,
                'key' => 'edit_room_types',
                'table_name' => 'room_types',
                'created_at' => '2020-10-15 11:16:35',
                'updated_at' => '2020-10-15 11:16:35',
            ),
            49 => 
            array (
                'id' => 50,
                'key' => 'add_room_types',
                'table_name' => 'room_types',
                'created_at' => '2020-10-15 11:16:35',
                'updated_at' => '2020-10-15 11:16:35',
            ),
            50 => 
            array (
                'id' => 51,
                'key' => 'delete_room_types',
                'table_name' => 'room_types',
                'created_at' => '2020-10-15 11:16:35',
                'updated_at' => '2020-10-15 11:16:35',
            ),
        ));
        
        
    }
}