<?php

use Illuminate\Database\Seeder;

class DataTypesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('data_types')->delete();
        
        \DB::table('data_types')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'users',
                'slug' => 'users',
                'display_name_singular' => 'User',
                'display_name_plural' => 'Users',
                'icon' => 'voyager-person',
                'model_name' => 'TCG\\Voyager\\Models\\User',
                'policy_name' => 'TCG\\Voyager\\Policies\\UserPolicy',
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerUserController',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2020-10-13 13:08:27',
                'updated_at' => '2020-10-13 13:08:27',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'menus',
                'slug' => 'menus',
                'display_name_singular' => 'Menu',
                'display_name_plural' => 'Menus',
                'icon' => 'voyager-list',
                'model_name' => 'TCG\\Voyager\\Models\\Menu',
                'policy_name' => NULL,
                'controller' => '',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2020-10-13 13:08:27',
                'updated_at' => '2020-10-13 13:08:27',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'roles',
                'slug' => 'roles',
                'display_name_singular' => 'Role',
                'display_name_plural' => 'Roles',
                'icon' => 'voyager-lock',
                'model_name' => 'TCG\\Voyager\\Models\\Role',
                'policy_name' => NULL,
                'controller' => 'TCG\\Voyager\\Http\\Controllers\\VoyagerRoleController',
                'description' => '',
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => NULL,
                'created_at' => '2020-10-13 13:08:27',
                'updated_at' => '2020-10-13 13:08:27',
            ),
            3 => 
            array (
                'id' => 7,
                'name' => 'accommodations',
                'slug' => 'accommodations',
                'display_name_singular' => 'Accommodation',
                'display_name_plural' => 'Accommodations',
                'icon' => NULL,
                'model_name' => 'App\\Models\\Accommodation',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":"title","scope":null}',
                'created_at' => '2020-10-13 13:24:21',
                'updated_at' => '2020-10-15 11:27:47',
            ),
            4 => 
            array (
                'id' => 9,
                'name' => 'accommodation_inventory',
                'slug' => 'accommodation-inventory',
                'display_name_singular' => 'Accommodation Inventory',
                'display_name_plural' => 'Accommodation Inventories',
                'icon' => NULL,
                'model_name' => 'App\\Models\\AccommodationInventory',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2020-10-15 09:18:14',
                'updated_at' => '2020-10-15 09:18:14',
            ),
            5 => 
            array (
                'id' => 11,
                'name' => 'accommodation_inventories',
                'slug' => 'accommodation-inventories',
                'display_name_singular' => 'Accommodation Inventory',
                'display_name_plural' => 'Accommodation Inventories',
                'icon' => NULL,
                'model_name' => 'App\\Models\\AccommodationInventory',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null,"scope":null}',
                'created_at' => '2020-10-15 09:20:59',
                'updated_at' => '2020-10-15 15:13:48',
            ),
            6 => 
            array (
                'id' => 13,
                'name' => 'board_types',
                'slug' => 'board-types',
                'display_name_singular' => 'Board Type',
                'display_name_plural' => 'Board Types',
                'icon' => NULL,
                'model_name' => 'App\\Models\\BoardType',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2020-10-15 10:03:54',
                'updated_at' => '2020-10-15 10:03:54',
            ),
            7 => 
            array (
                'id' => 14,
                'name' => 'room_types',
                'slug' => 'room-types',
                'display_name_singular' => 'Room Type',
                'display_name_plural' => 'Room Types',
                'icon' => NULL,
                'model_name' => 'App\\Models\\RoomType',
                'policy_name' => NULL,
                'controller' => NULL,
                'description' => NULL,
                'generate_permissions' => 1,
                'server_side' => 0,
                'details' => '{"order_column":null,"order_display_column":null,"order_direction":"asc","default_search_key":null}',
                'created_at' => '2020-10-15 11:16:35',
                'updated_at' => '2020-10-15 11:16:35',
            ),
        ));
        
        
    }
}