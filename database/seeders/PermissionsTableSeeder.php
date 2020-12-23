<?php
namespace Database\Seeders;
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
                'id' => 32,
                'key' => 'browse_accommodation_inventory',
                'table_name' => 'accommodation_inventory',
                'created_at' => '2020-10-15 09:18:14',
                'updated_at' => '2020-10-15 09:18:14',
            ),
            27 => 
            array (
                'id' => 33,
                'key' => 'read_accommodation_inventory',
                'table_name' => 'accommodation_inventory',
                'created_at' => '2020-10-15 09:18:14',
                'updated_at' => '2020-10-15 09:18:14',
            ),
            28 => 
            array (
                'id' => 34,
                'key' => 'edit_accommodation_inventory',
                'table_name' => 'accommodation_inventory',
                'created_at' => '2020-10-15 09:18:14',
                'updated_at' => '2020-10-15 09:18:14',
            ),
            29 => 
            array (
                'id' => 35,
                'key' => 'add_accommodation_inventory',
                'table_name' => 'accommodation_inventory',
                'created_at' => '2020-10-15 09:18:14',
                'updated_at' => '2020-10-15 09:18:14',
            ),
            30 => 
            array (
                'id' => 36,
                'key' => 'delete_accommodation_inventory',
                'table_name' => 'accommodation_inventory',
                'created_at' => '2020-10-15 09:18:14',
                'updated_at' => '2020-10-15 09:18:14',
            ),
            31 => 
            array (
                'id' => 37,
                'key' => 'browse_accommodation_inventories',
                'table_name' => 'accommodation_inventories',
                'created_at' => '2020-10-15 09:21:00',
                'updated_at' => '2020-10-15 09:21:00',
            ),
            32 => 
            array (
                'id' => 38,
                'key' => 'read_accommodation_inventories',
                'table_name' => 'accommodation_inventories',
                'created_at' => '2020-10-15 09:21:00',
                'updated_at' => '2020-10-15 09:21:00',
            ),
            33 => 
            array (
                'id' => 39,
                'key' => 'edit_accommodation_inventories',
                'table_name' => 'accommodation_inventories',
                'created_at' => '2020-10-15 09:21:00',
                'updated_at' => '2020-10-15 09:21:00',
            ),
            34 => 
            array (
                'id' => 40,
                'key' => 'add_accommodation_inventories',
                'table_name' => 'accommodation_inventories',
                'created_at' => '2020-10-15 09:21:00',
                'updated_at' => '2020-10-15 09:21:00',
            ),
            35 => 
            array (
                'id' => 41,
                'key' => 'delete_accommodation_inventories',
                'table_name' => 'accommodation_inventories',
                'created_at' => '2020-10-15 09:21:00',
                'updated_at' => '2020-10-15 09:21:00',
            ),
            36 => 
            array (
                'id' => 42,
                'key' => 'browse_board_types',
                'table_name' => 'board_types',
                'created_at' => '2020-10-15 10:03:54',
                'updated_at' => '2020-10-15 10:03:54',
            ),
            37 => 
            array (
                'id' => 43,
                'key' => 'read_board_types',
                'table_name' => 'board_types',
                'created_at' => '2020-10-15 10:03:54',
                'updated_at' => '2020-10-15 10:03:54',
            ),
            38 => 
            array (
                'id' => 44,
                'key' => 'edit_board_types',
                'table_name' => 'board_types',
                'created_at' => '2020-10-15 10:03:54',
                'updated_at' => '2020-10-15 10:03:54',
            ),
            39 => 
            array (
                'id' => 45,
                'key' => 'add_board_types',
                'table_name' => 'board_types',
                'created_at' => '2020-10-15 10:03:54',
                'updated_at' => '2020-10-15 10:03:54',
            ),
            40 => 
            array (
                'id' => 46,
                'key' => 'delete_board_types',
                'table_name' => 'board_types',
                'created_at' => '2020-10-15 10:03:54',
                'updated_at' => '2020-10-15 10:03:54',
            ),
            41 => 
            array (
                'id' => 47,
                'key' => 'browse_room_types',
                'table_name' => 'room_types',
                'created_at' => '2020-10-15 11:16:35',
                'updated_at' => '2020-10-15 11:16:35',
            ),
            42 => 
            array (
                'id' => 48,
                'key' => 'read_room_types',
                'table_name' => 'room_types',
                'created_at' => '2020-10-15 11:16:35',
                'updated_at' => '2020-10-15 11:16:35',
            ),
            43 => 
            array (
                'id' => 49,
                'key' => 'edit_room_types',
                'table_name' => 'room_types',
                'created_at' => '2020-10-15 11:16:35',
                'updated_at' => '2020-10-15 11:16:35',
            ),
            44 => 
            array (
                'id' => 50,
                'key' => 'add_room_types',
                'table_name' => 'room_types',
                'created_at' => '2020-10-15 11:16:35',
                'updated_at' => '2020-10-15 11:16:35',
            ),
            45 => 
            array (
                'id' => 51,
                'key' => 'delete_room_types',
                'table_name' => 'room_types',
                'created_at' => '2020-10-15 11:16:35',
                'updated_at' => '2020-10-15 11:16:35',
            ),
            46 => 
            array (
                'id' => 52,
                'key' => 'browse_model_log',
                'table_name' => 'model_log',
                'created_at' => '2020-10-15 14:41:14',
                'updated_at' => '2020-10-15 14:41:14',
            ),
            47 => 
            array (
                'id' => 53,
                'key' => 'clear_model_log',
                'table_name' => 'model_log',
                'created_at' => '2020-10-15 14:41:14',
                'updated_at' => '2020-10-15 14:41:14',
            ),
            48 => 
            array (
                'id' => 59,
                'key' => 'browse_regions',
                'table_name' => 'regions',
                'created_at' => '2020-10-22 12:22:09',
                'updated_at' => '2020-10-22 12:22:09',
            ),
            49 => 
            array (
                'id' => 60,
                'key' => 'read_regions',
                'table_name' => 'regions',
                'created_at' => '2020-10-22 12:22:09',
                'updated_at' => '2020-10-22 12:22:09',
            ),
            50 => 
            array (
                'id' => 61,
                'key' => 'edit_regions',
                'table_name' => 'regions',
                'created_at' => '2020-10-22 12:22:09',
                'updated_at' => '2020-10-22 12:22:09',
            ),
            51 => 
            array (
                'id' => 62,
                'key' => 'add_regions',
                'table_name' => 'regions',
                'created_at' => '2020-10-22 12:22:09',
                'updated_at' => '2020-10-22 12:22:09',
            ),
            52 => 
            array (
                'id' => 63,
                'key' => 'delete_regions',
                'table_name' => 'regions',
                'created_at' => '2020-10-22 12:22:09',
                'updated_at' => '2020-10-22 12:22:09',
            ),
            53 => 
            array (
                'id' => 64,
                'key' => 'browse_countries',
                'table_name' => 'countries',
                'created_at' => '2020-10-22 12:25:43',
                'updated_at' => '2020-10-22 12:25:43',
            ),
            54 => 
            array (
                'id' => 65,
                'key' => 'read_countries',
                'table_name' => 'countries',
                'created_at' => '2020-10-22 12:25:43',
                'updated_at' => '2020-10-22 12:25:43',
            ),
            55 => 
            array (
                'id' => 66,
                'key' => 'edit_countries',
                'table_name' => 'countries',
                'created_at' => '2020-10-22 12:25:43',
                'updated_at' => '2020-10-22 12:25:43',
            ),
            56 => 
            array (
                'id' => 67,
                'key' => 'add_countries',
                'table_name' => 'countries',
                'created_at' => '2020-10-22 12:25:43',
                'updated_at' => '2020-10-22 12:25:43',
            ),
            57 => 
            array (
                'id' => 68,
                'key' => 'delete_countries',
                'table_name' => 'countries',
                'created_at' => '2020-10-22 12:25:43',
                'updated_at' => '2020-10-22 12:25:43',
            ),
            58 => 
            array (
                'id' => 69,
                'key' => 'browse_location_types',
                'table_name' => 'location_types',
                'created_at' => '2020-10-22 12:52:30',
                'updated_at' => '2020-10-22 12:52:30',
            ),
            59 => 
            array (
                'id' => 70,
                'key' => 'read_location_types',
                'table_name' => 'location_types',
                'created_at' => '2020-10-22 12:52:30',
                'updated_at' => '2020-10-22 12:52:30',
            ),
            60 => 
            array (
                'id' => 71,
                'key' => 'edit_location_types',
                'table_name' => 'location_types',
                'created_at' => '2020-10-22 12:52:30',
                'updated_at' => '2020-10-22 12:52:30',
            ),
            61 => 
            array (
                'id' => 72,
                'key' => 'add_location_types',
                'table_name' => 'location_types',
                'created_at' => '2020-10-22 12:52:30',
                'updated_at' => '2020-10-22 12:52:30',
            ),
            62 => 
            array (
                'id' => 73,
                'key' => 'delete_location_types',
                'table_name' => 'location_types',
                'created_at' => '2020-10-22 12:52:30',
                'updated_at' => '2020-10-22 12:52:30',
            ),
            63 => 
            array (
                'id' => 74,
                'key' => 'browse_locations',
                'table_name' => 'locations',
                'created_at' => '2020-10-22 13:00:12',
                'updated_at' => '2020-10-22 13:00:12',
            ),
            64 => 
            array (
                'id' => 75,
                'key' => 'read_locations',
                'table_name' => 'locations',
                'created_at' => '2020-10-22 13:00:12',
                'updated_at' => '2020-10-22 13:00:12',
            ),
            65 => 
            array (
                'id' => 76,
                'key' => 'edit_locations',
                'table_name' => 'locations',
                'created_at' => '2020-10-22 13:00:12',
                'updated_at' => '2020-10-22 13:00:12',
            ),
            66 => 
            array (
                'id' => 77,
                'key' => 'add_locations',
                'table_name' => 'locations',
                'created_at' => '2020-10-22 13:00:12',
                'updated_at' => '2020-10-22 13:00:12',
            ),
            67 => 
            array (
                'id' => 78,
                'key' => 'delete_locations',
                'table_name' => 'locations',
                'created_at' => '2020-10-22 13:00:12',
                'updated_at' => '2020-10-22 13:00:12',
            ),
            68 => 
            array (
                'id' => 84,
                'key' => 'browse_accommodations',
                'table_name' => 'accommodations',
                'created_at' => '2020-10-22 14:27:11',
                'updated_at' => '2020-10-22 14:27:11',
            ),
            69 => 
            array (
                'id' => 85,
                'key' => 'read_accommodations',
                'table_name' => 'accommodations',
                'created_at' => '2020-10-22 14:27:11',
                'updated_at' => '2020-10-22 14:27:11',
            ),
            70 => 
            array (
                'id' => 86,
                'key' => 'edit_accommodations',
                'table_name' => 'accommodations',
                'created_at' => '2020-10-22 14:27:11',
                'updated_at' => '2020-10-22 14:27:11',
            ),
            71 => 
            array (
                'id' => 87,
                'key' => 'add_accommodations',
                'table_name' => 'accommodations',
                'created_at' => '2020-10-22 14:27:11',
                'updated_at' => '2020-10-22 14:27:11',
            ),
            72 => 
            array (
                'id' => 88,
                'key' => 'delete_accommodations',
                'table_name' => 'accommodations',
                'created_at' => '2020-10-22 14:27:11',
                'updated_at' => '2020-10-22 14:27:11',
            ),
            73 => 
            array (
                'id' => 104,
                'key' => 'browse_airlines',
                'table_name' => 'airlines',
                'created_at' => '2020-11-03 10:45:18',
                'updated_at' => '2020-11-03 10:45:18',
            ),
            74 => 
            array (
                'id' => 105,
                'key' => 'read_airlines',
                'table_name' => 'airlines',
                'created_at' => '2020-11-03 10:45:18',
                'updated_at' => '2020-11-03 10:45:18',
            ),
            75 => 
            array (
                'id' => 106,
                'key' => 'edit_airlines',
                'table_name' => 'airlines',
                'created_at' => '2020-11-03 10:45:18',
                'updated_at' => '2020-11-03 10:45:18',
            ),
            76 => 
            array (
                'id' => 107,
                'key' => 'add_airlines',
                'table_name' => 'airlines',
                'created_at' => '2020-11-03 10:45:18',
                'updated_at' => '2020-11-03 10:45:18',
            ),
            77 => 
            array (
                'id' => 108,
                'key' => 'delete_airlines',
                'table_name' => 'airlines',
                'created_at' => '2020-11-03 10:45:18',
                'updated_at' => '2020-11-03 10:45:18',
            ),
            78 => 
            array (
                'id' => 109,
                'key' => 'browse_airports',
                'table_name' => 'airports',
                'created_at' => '2020-11-03 10:47:14',
                'updated_at' => '2020-11-03 10:47:14',
            ),
            79 => 
            array (
                'id' => 110,
                'key' => 'read_airports',
                'table_name' => 'airports',
                'created_at' => '2020-11-03 10:47:14',
                'updated_at' => '2020-11-03 10:47:14',
            ),
            80 => 
            array (
                'id' => 111,
                'key' => 'edit_airports',
                'table_name' => 'airports',
                'created_at' => '2020-11-03 10:47:14',
                'updated_at' => '2020-11-03 10:47:14',
            ),
            81 => 
            array (
                'id' => 112,
                'key' => 'add_airports',
                'table_name' => 'airports',
                'created_at' => '2020-11-03 10:47:14',
                'updated_at' => '2020-11-03 10:47:14',
            ),
            82 => 
            array (
                'id' => 113,
                'key' => 'delete_airports',
                'table_name' => 'airports',
                'created_at' => '2020-11-03 10:47:14',
                'updated_at' => '2020-11-03 10:47:14',
            ),
            83 => 
            array (
                'id' => 114,
                'key' => 'browse_flights',
                'table_name' => 'flights',
                'created_at' => '2020-11-03 10:47:42',
                'updated_at' => '2020-11-03 10:47:42',
            ),
            84 => 
            array (
                'id' => 115,
                'key' => 'read_flights',
                'table_name' => 'flights',
                'created_at' => '2020-11-03 10:47:42',
                'updated_at' => '2020-11-03 10:47:42',
            ),
            85 => 
            array (
                'id' => 116,
                'key' => 'edit_flights',
                'table_name' => 'flights',
                'created_at' => '2020-11-03 10:47:42',
                'updated_at' => '2020-11-03 10:47:42',
            ),
            86 => 
            array (
                'id' => 117,
                'key' => 'add_flights',
                'table_name' => 'flights',
                'created_at' => '2020-11-03 10:47:42',
                'updated_at' => '2020-11-03 10:47:42',
            ),
            87 => 
            array (
                'id' => 118,
                'key' => 'delete_flights',
                'table_name' => 'flights',
                'created_at' => '2020-11-03 10:47:42',
                'updated_at' => '2020-11-03 10:47:42',
            ),
            88 => 
            array (
                'id' => 119,
                'key' => 'browse_travel_classes',
                'table_name' => 'travel_classes',
                'created_at' => '2020-11-03 16:06:58',
                'updated_at' => '2020-11-03 16:06:58',
            ),
            89 => 
            array (
                'id' => 120,
                'key' => 'read_travel_classes',
                'table_name' => 'travel_classes',
                'created_at' => '2020-11-03 16:06:58',
                'updated_at' => '2020-11-03 16:06:58',
            ),
            90 => 
            array (
                'id' => 121,
                'key' => 'edit_travel_classes',
                'table_name' => 'travel_classes',
                'created_at' => '2020-11-03 16:06:58',
                'updated_at' => '2020-11-03 16:06:58',
            ),
            91 => 
            array (
                'id' => 122,
                'key' => 'add_travel_classes',
                'table_name' => 'travel_classes',
                'created_at' => '2020-11-03 16:06:58',
                'updated_at' => '2020-11-03 16:06:58',
            ),
            92 => 
            array (
                'id' => 123,
                'key' => 'delete_travel_classes',
                'table_name' => 'travel_classes',
                'created_at' => '2020-11-03 16:06:58',
                'updated_at' => '2020-11-03 16:06:58',
            ),
            93 => 
            array (
                'id' => 124,
                'key' => 'browse_flight_inventories',
                'table_name' => 'flight_inventories',
                'created_at' => '2020-11-03 18:21:33',
                'updated_at' => '2020-11-03 18:21:33',
            ),
            94 => 
            array (
                'id' => 125,
                'key' => 'read_flight_inventories',
                'table_name' => 'flight_inventories',
                'created_at' => '2020-11-03 18:21:33',
                'updated_at' => '2020-11-03 18:21:33',
            ),
            95 => 
            array (
                'id' => 126,
                'key' => 'edit_flight_inventories',
                'table_name' => 'flight_inventories',
                'created_at' => '2020-11-03 18:21:33',
                'updated_at' => '2020-11-03 18:21:33',
            ),
            96 => 
            array (
                'id' => 127,
                'key' => 'add_flight_inventories',
                'table_name' => 'flight_inventories',
                'created_at' => '2020-11-03 18:21:33',
                'updated_at' => '2020-11-03 18:21:33',
            ),
            97 => 
            array (
                'id' => 128,
                'key' => 'delete_flight_inventories',
                'table_name' => 'flight_inventories',
                'created_at' => '2020-11-03 18:21:33',
                'updated_at' => '2020-11-03 18:21:33',
            ),
            98 => 
            array (
                'id' => 129,
                'key' => 'browse_activity_types',
                'table_name' => 'activity_types',
                'created_at' => '2020-11-04 14:48:19',
                'updated_at' => '2020-11-04 14:48:19',
            ),
            99 => 
            array (
                'id' => 130,
                'key' => 'read_activity_types',
                'table_name' => 'activity_types',
                'created_at' => '2020-11-04 14:48:19',
                'updated_at' => '2020-11-04 14:48:19',
            ),
            100 => 
            array (
                'id' => 131,
                'key' => 'edit_activity_types',
                'table_name' => 'activity_types',
                'created_at' => '2020-11-04 14:48:19',
                'updated_at' => '2020-11-04 14:48:19',
            ),
            101 => 
            array (
                'id' => 132,
                'key' => 'add_activity_types',
                'table_name' => 'activity_types',
                'created_at' => '2020-11-04 14:48:19',
                'updated_at' => '2020-11-04 14:48:19',
            ),
            102 => 
            array (
                'id' => 133,
                'key' => 'delete_activity_types',
                'table_name' => 'activity_types',
                'created_at' => '2020-11-04 14:48:19',
                'updated_at' => '2020-11-04 14:48:19',
            ),
            103 => 
            array (
                'id' => 134,
                'key' => 'browse_ticket_types',
                'table_name' => 'ticket_types',
                'created_at' => '2020-11-04 16:01:42',
                'updated_at' => '2020-11-04 16:01:42',
            ),
            104 => 
            array (
                'id' => 135,
                'key' => 'read_ticket_types',
                'table_name' => 'ticket_types',
                'created_at' => '2020-11-04 16:01:42',
                'updated_at' => '2020-11-04 16:01:42',
            ),
            105 => 
            array (
                'id' => 136,
                'key' => 'edit_ticket_types',
                'table_name' => 'ticket_types',
                'created_at' => '2020-11-04 16:01:42',
                'updated_at' => '2020-11-04 16:01:42',
            ),
            106 => 
            array (
                'id' => 137,
                'key' => 'add_ticket_types',
                'table_name' => 'ticket_types',
                'created_at' => '2020-11-04 16:01:42',
                'updated_at' => '2020-11-04 16:01:42',
            ),
            107 => 
            array (
                'id' => 138,
                'key' => 'delete_ticket_types',
                'table_name' => 'ticket_types',
                'created_at' => '2020-11-04 16:01:42',
                'updated_at' => '2020-11-04 16:01:42',
            ),
            108 => 
            array (
                'id' => 139,
                'key' => 'browse_activities',
                'table_name' => 'activities',
                'created_at' => '2020-11-04 16:02:46',
                'updated_at' => '2020-11-04 16:02:46',
            ),
            109 => 
            array (
                'id' => 140,
                'key' => 'read_activities',
                'table_name' => 'activities',
                'created_at' => '2020-11-04 16:02:46',
                'updated_at' => '2020-11-04 16:02:46',
            ),
            110 => 
            array (
                'id' => 141,
                'key' => 'edit_activities',
                'table_name' => 'activities',
                'created_at' => '2020-11-04 16:02:46',
                'updated_at' => '2020-11-04 16:02:46',
            ),
            111 => 
            array (
                'id' => 142,
                'key' => 'add_activities',
                'table_name' => 'activities',
                'created_at' => '2020-11-04 16:02:46',
                'updated_at' => '2020-11-04 16:02:46',
            ),
            112 => 
            array (
                'id' => 143,
                'key' => 'delete_activities',
                'table_name' => 'activities',
                'created_at' => '2020-11-04 16:02:46',
                'updated_at' => '2020-11-04 16:02:46',
            ),
            113 => 
            array (
                'id' => 144,
                'key' => 'browse_activity_inventories',
                'table_name' => 'activity_inventories',
                'created_at' => '2020-11-04 18:08:44',
                'updated_at' => '2020-11-04 18:08:44',
            ),
            114 => 
            array (
                'id' => 145,
                'key' => 'read_activity_inventories',
                'table_name' => 'activity_inventories',
                'created_at' => '2020-11-04 18:08:44',
                'updated_at' => '2020-11-04 18:08:44',
            ),
            115 => 
            array (
                'id' => 146,
                'key' => 'edit_activity_inventories',
                'table_name' => 'activity_inventories',
                'created_at' => '2020-11-04 18:08:44',
                'updated_at' => '2020-11-04 18:08:44',
            ),
            116 => 
            array (
                'id' => 147,
                'key' => 'add_activity_inventories',
                'table_name' => 'activity_inventories',
                'created_at' => '2020-11-04 18:08:44',
                'updated_at' => '2020-11-04 18:08:44',
            ),
            117 => 
            array (
                'id' => 148,
                'key' => 'delete_activity_inventories',
                'table_name' => 'activity_inventories',
                'created_at' => '2020-11-04 18:08:44',
                'updated_at' => '2020-11-04 18:08:44',
            ),
            118 => 
            array (
                'id' => 149,
                'key' => 'browse_transports',
                'table_name' => 'transports',
                'created_at' => '2020-11-05 12:08:07',
                'updated_at' => '2020-11-05 12:08:07',
            ),
            119 => 
            array (
                'id' => 150,
                'key' => 'read_transports',
                'table_name' => 'transports',
                'created_at' => '2020-11-05 12:08:07',
                'updated_at' => '2020-11-05 12:08:07',
            ),
            120 => 
            array (
                'id' => 151,
                'key' => 'edit_transports',
                'table_name' => 'transports',
                'created_at' => '2020-11-05 12:08:07',
                'updated_at' => '2020-11-05 12:08:07',
            ),
            121 => 
            array (
                'id' => 152,
                'key' => 'add_transports',
                'table_name' => 'transports',
                'created_at' => '2020-11-05 12:08:07',
                'updated_at' => '2020-11-05 12:08:07',
            ),
            122 => 
            array (
                'id' => 153,
                'key' => 'delete_transports',
                'table_name' => 'transports',
                'created_at' => '2020-11-05 12:08:07',
                'updated_at' => '2020-11-05 12:08:07',
            ),
            123 => 
            array (
                'id' => 154,
                'key' => 'browse_transport_types',
                'table_name' => 'transport_types',
                'created_at' => '2020-11-05 12:11:44',
                'updated_at' => '2020-11-05 12:11:44',
            ),
            124 => 
            array (
                'id' => 155,
                'key' => 'read_transport_types',
                'table_name' => 'transport_types',
                'created_at' => '2020-11-05 12:11:44',
                'updated_at' => '2020-11-05 12:11:44',
            ),
            125 => 
            array (
                'id' => 156,
                'key' => 'edit_transport_types',
                'table_name' => 'transport_types',
                'created_at' => '2020-11-05 12:11:44',
                'updated_at' => '2020-11-05 12:11:44',
            ),
            126 => 
            array (
                'id' => 157,
                'key' => 'add_transport_types',
                'table_name' => 'transport_types',
                'created_at' => '2020-11-05 12:11:44',
                'updated_at' => '2020-11-05 12:11:44',
            ),
            127 => 
            array (
                'id' => 158,
                'key' => 'delete_transport_types',
                'table_name' => 'transport_types',
                'created_at' => '2020-11-05 12:11:44',
                'updated_at' => '2020-11-05 12:11:44',
            ),
            128 => 
            array (
                'id' => 159,
                'key' => 'browse_operators',
                'table_name' => 'operators',
                'created_at' => '2020-11-05 12:36:33',
                'updated_at' => '2020-11-05 12:36:33',
            ),
            129 => 
            array (
                'id' => 160,
                'key' => 'read_operators',
                'table_name' => 'operators',
                'created_at' => '2020-11-05 12:36:33',
                'updated_at' => '2020-11-05 12:36:33',
            ),
            130 => 
            array (
                'id' => 161,
                'key' => 'edit_operators',
                'table_name' => 'operators',
                'created_at' => '2020-11-05 12:36:33',
                'updated_at' => '2020-11-05 12:36:33',
            ),
            131 => 
            array (
                'id' => 162,
                'key' => 'add_operators',
                'table_name' => 'operators',
                'created_at' => '2020-11-05 12:36:33',
                'updated_at' => '2020-11-05 12:36:33',
            ),
            132 => 
            array (
                'id' => 163,
                'key' => 'delete_operators',
                'table_name' => 'operators',
                'created_at' => '2020-11-05 12:36:33',
                'updated_at' => '2020-11-05 12:36:33',
            ),
            133 => 
            array (
                'id' => 164,
                'key' => 'browse_transport_inventories',
                'table_name' => 'transport_inventories',
                'created_at' => '2020-11-05 12:38:06',
                'updated_at' => '2020-11-05 12:38:06',
            ),
            134 => 
            array (
                'id' => 165,
                'key' => 'read_transport_inventories',
                'table_name' => 'transport_inventories',
                'created_at' => '2020-11-05 12:38:06',
                'updated_at' => '2020-11-05 12:38:06',
            ),
            135 => 
            array (
                'id' => 166,
                'key' => 'edit_transport_inventories',
                'table_name' => 'transport_inventories',
                'created_at' => '2020-11-05 12:38:06',
                'updated_at' => '2020-11-05 12:38:06',
            ),
            136 => 
            array (
                'id' => 167,
                'key' => 'add_transport_inventories',
                'table_name' => 'transport_inventories',
                'created_at' => '2020-11-05 12:38:06',
                'updated_at' => '2020-11-05 12:38:06',
            ),
            137 => 
            array (
                'id' => 168,
                'key' => 'delete_transport_inventories',
                'table_name' => 'transport_inventories',
                'created_at' => '2020-11-05 12:38:06',
                'updated_at' => '2020-11-05 12:38:06',
            ),
            138 => 
            array (
                'id' => 169,
                'key' => 'browse_tours',
                'table_name' => 'tours',
                'created_at' => '2020-11-16 08:43:02',
                'updated_at' => '2020-11-16 08:43:02',
            ),
            139 => 
            array (
                'id' => 170,
                'key' => 'read_tours',
                'table_name' => 'tours',
                'created_at' => '2020-11-16 08:43:02',
                'updated_at' => '2020-11-16 08:43:02',
            ),
            140 => 
            array (
                'id' => 171,
                'key' => 'edit_tours',
                'table_name' => 'tours',
                'created_at' => '2020-11-16 08:43:02',
                'updated_at' => '2020-11-16 08:43:02',
            ),
            141 => 
            array (
                'id' => 172,
                'key' => 'add_tours',
                'table_name' => 'tours',
                'created_at' => '2020-11-16 08:43:02',
                'updated_at' => '2020-11-16 08:43:02',
            ),
            142 => 
            array (
                'id' => 173,
                'key' => 'delete_tours',
                'table_name' => 'tours',
                'created_at' => '2020-11-16 08:43:02',
                'updated_at' => '2020-11-16 08:43:02',
            ),
            143 => 
            array (
                'id' => 174,
                'key' => 'browse_events',
                'table_name' => 'events',
                'created_at' => '2020-11-20 17:12:37',
                'updated_at' => '2020-11-20 17:12:37',
            ),
            144 => 
            array (
                'id' => 175,
                'key' => 'read_events',
                'table_name' => 'events',
                'created_at' => '2020-11-20 17:12:37',
                'updated_at' => '2020-11-20 17:12:37',
            ),
            145 => 
            array (
                'id' => 176,
                'key' => 'edit_events',
                'table_name' => 'events',
                'created_at' => '2020-11-20 17:12:37',
                'updated_at' => '2020-11-20 17:12:37',
            ),
            146 => 
            array (
                'id' => 177,
                'key' => 'add_events',
                'table_name' => 'events',
                'created_at' => '2020-11-20 17:12:37',
                'updated_at' => '2020-11-20 17:12:37',
            ),
            147 => 
            array (
                'id' => 178,
                'key' => 'delete_events',
                'table_name' => 'events',
                'created_at' => '2020-11-20 17:12:37',
                'updated_at' => '2020-11-20 17:12:37',
            ),
            148 => 
            array (
                'id' => 189,
                'key' => 'browse_payment_schedules',
                'table_name' => 'payment_schedules',
                'created_at' => '2020-11-23 14:02:22',
                'updated_at' => '2020-11-23 14:02:22',
            ),
            149 => 
            array (
                'id' => 190,
                'key' => 'read_payment_schedules',
                'table_name' => 'payment_schedules',
                'created_at' => '2020-11-23 14:02:22',
                'updated_at' => '2020-11-23 14:02:22',
            ),
            150 => 
            array (
                'id' => 191,
                'key' => 'edit_payment_schedules',
                'table_name' => 'payment_schedules',
                'created_at' => '2020-11-23 14:02:22',
                'updated_at' => '2020-11-23 14:02:22',
            ),
            151 => 
            array (
                'id' => 192,
                'key' => 'add_payment_schedules',
                'table_name' => 'payment_schedules',
                'created_at' => '2020-11-23 14:02:22',
                'updated_at' => '2020-11-23 14:02:22',
            ),
            152 => 
            array (
                'id' => 193,
                'key' => 'delete_payment_schedules',
                'table_name' => 'payment_schedules',
                'created_at' => '2020-11-23 14:02:22',
                'updated_at' => '2020-11-23 14:02:22',
            ),
            153 => 
            array (
                'id' => 194,
                'key' => 'browse_payment_installments',
                'table_name' => 'payment_installments',
                'created_at' => '2020-11-23 14:03:41',
                'updated_at' => '2020-11-23 14:03:41',
            ),
            154 => 
            array (
                'id' => 195,
                'key' => 'read_payment_installments',
                'table_name' => 'payment_installments',
                'created_at' => '2020-11-23 14:03:41',
                'updated_at' => '2020-11-23 14:03:41',
            ),
            155 => 
            array (
                'id' => 196,
                'key' => 'edit_payment_installments',
                'table_name' => 'payment_installments',
                'created_at' => '2020-11-23 14:03:41',
                'updated_at' => '2020-11-23 14:03:41',
            ),
            156 => 
            array (
                'id' => 197,
                'key' => 'add_payment_installments',
                'table_name' => 'payment_installments',
                'created_at' => '2020-11-23 14:03:41',
                'updated_at' => '2020-11-23 14:03:41',
            ),
            157 => 
            array (
                'id' => 198,
                'key' => 'delete_payment_installments',
                'table_name' => 'payment_installments',
                'created_at' => '2020-11-23 14:03:41',
                'updated_at' => '2020-11-23 14:03:41',
            ),
            158 => 
            array (
                'id' => 199,
                'key' => 'browse_orders',
                'table_name' => 'orders',
                'created_at' => '2020-11-25 15:38:18',
                'updated_at' => '2020-11-25 15:38:18',
            ),
            159 => 
            array (
                'id' => 200,
                'key' => 'read_orders',
                'table_name' => 'orders',
                'created_at' => '2020-11-25 15:38:18',
                'updated_at' => '2020-11-25 15:38:18',
            ),
            160 => 
            array (
                'id' => 201,
                'key' => 'edit_orders',
                'table_name' => 'orders',
                'created_at' => '2020-11-25 15:38:18',
                'updated_at' => '2020-11-25 15:38:18',
            ),
            161 => 
            array (
                'id' => 202,
                'key' => 'add_orders',
                'table_name' => 'orders',
                'created_at' => '2020-11-25 15:38:18',
                'updated_at' => '2020-11-25 15:38:18',
            ),
            162 => 
            array (
                'id' => 203,
                'key' => 'delete_orders',
                'table_name' => 'orders',
                'created_at' => '2020-11-25 15:38:18',
                'updated_at' => '2020-11-25 15:38:18',
            ),
            163 => 
            array (
                'id' => 204,
                'key' => 'browse_orders_customers',
                'table_name' => 'orders_customers',
                'created_at' => '2020-11-25 16:08:05',
                'updated_at' => '2020-11-25 16:08:05',
            ),
            164 => 
            array (
                'id' => 205,
                'key' => 'read_orders_customers',
                'table_name' => 'orders_customers',
                'created_at' => '2020-11-25 16:08:05',
                'updated_at' => '2020-11-25 16:08:05',
            ),
            165 => 
            array (
                'id' => 206,
                'key' => 'edit_orders_customers',
                'table_name' => 'orders_customers',
                'created_at' => '2020-11-25 16:08:05',
                'updated_at' => '2020-11-25 16:08:05',
            ),
            166 => 
            array (
                'id' => 207,
                'key' => 'add_orders_customers',
                'table_name' => 'orders_customers',
                'created_at' => '2020-11-25 16:08:05',
                'updated_at' => '2020-11-25 16:08:05',
            ),
            167 => 
            array (
                'id' => 208,
                'key' => 'delete_orders_customers',
                'table_name' => 'orders_customers',
                'created_at' => '2020-11-25 16:08:05',
                'updated_at' => '2020-11-25 16:08:05',
            ),
            168 => 
            array (
                'id' => 209,
                'key' => 'browse_order_statuses',
                'table_name' => 'order_statuses',
                'created_at' => '2020-11-26 08:51:04',
                'updated_at' => '2020-11-26 08:51:04',
            ),
            169 => 
            array (
                'id' => 210,
                'key' => 'read_order_statuses',
                'table_name' => 'order_statuses',
                'created_at' => '2020-11-26 08:51:04',
                'updated_at' => '2020-11-26 08:51:04',
            ),
            170 => 
            array (
                'id' => 211,
                'key' => 'edit_order_statuses',
                'table_name' => 'order_statuses',
                'created_at' => '2020-11-26 08:51:04',
                'updated_at' => '2020-11-26 08:51:04',
            ),
            171 => 
            array (
                'id' => 212,
                'key' => 'add_order_statuses',
                'table_name' => 'order_statuses',
                'created_at' => '2020-11-26 08:51:04',
                'updated_at' => '2020-11-26 08:51:04',
            ),
            172 => 
            array (
                'id' => 213,
                'key' => 'delete_order_statuses',
                'table_name' => 'order_statuses',
                'created_at' => '2020-11-26 08:51:04',
                'updated_at' => '2020-11-26 08:51:04',
            ),
            173 => 
            array (
                'id' => 214,
                'key' => 'browse_customers',
                'table_name' => 'customers',
                'created_at' => '2020-11-26 09:18:56',
                'updated_at' => '2020-11-26 09:18:56',
            ),
            174 => 
            array (
                'id' => 215,
                'key' => 'read_customers',
                'table_name' => 'customers',
                'created_at' => '2020-11-26 09:18:56',
                'updated_at' => '2020-11-26 09:18:56',
            ),
            175 => 
            array (
                'id' => 216,
                'key' => 'edit_customers',
                'table_name' => 'customers',
                'created_at' => '2020-11-26 09:18:56',
                'updated_at' => '2020-11-26 09:18:56',
            ),
            176 => 
            array (
                'id' => 217,
                'key' => 'add_customers',
                'table_name' => 'customers',
                'created_at' => '2020-11-26 09:18:56',
                'updated_at' => '2020-11-26 09:18:56',
            ),
            177 => 
            array (
                'id' => 218,
                'key' => 'delete_customers',
                'table_name' => 'customers',
                'created_at' => '2020-11-26 09:18:56',
                'updated_at' => '2020-11-26 09:18:56',
            ),
            178 => 
            array (
                'id' => 224,
                'key' => 'browse_invoices',
                'table_name' => 'invoices',
                'created_at' => '2020-11-27 09:18:30',
                'updated_at' => '2020-11-27 09:18:30',
            ),
            179 => 
            array (
                'id' => 225,
                'key' => 'read_invoices',
                'table_name' => 'invoices',
                'created_at' => '2020-11-27 09:18:30',
                'updated_at' => '2020-11-27 09:18:30',
            ),
            180 => 
            array (
                'id' => 226,
                'key' => 'edit_invoices',
                'table_name' => 'invoices',
                'created_at' => '2020-11-27 09:18:30',
                'updated_at' => '2020-11-27 09:18:30',
            ),
            181 => 
            array (
                'id' => 227,
                'key' => 'add_invoices',
                'table_name' => 'invoices',
                'created_at' => '2020-11-27 09:18:30',
                'updated_at' => '2020-11-27 09:18:30',
            ),
            182 => 
            array (
                'id' => 228,
                'key' => 'delete_invoices',
                'table_name' => 'invoices',
                'created_at' => '2020-11-27 09:18:30',
                'updated_at' => '2020-11-27 09:18:30',
            ),
            183 => 
            array (
                'id' => 229,
                'key' => 'browse_tour_component_types',
                'table_name' => 'tour_component_types',
                'created_at' => '2020-12-08 15:33:09',
                'updated_at' => '2020-12-08 15:33:09',
            ),
            184 => 
            array (
                'id' => 230,
                'key' => 'read_tour_component_types',
                'table_name' => 'tour_component_types',
                'created_at' => '2020-12-08 15:33:09',
                'updated_at' => '2020-12-08 15:33:09',
            ),
            185 => 
            array (
                'id' => 231,
                'key' => 'edit_tour_component_types',
                'table_name' => 'tour_component_types',
                'created_at' => '2020-12-08 15:33:09',
                'updated_at' => '2020-12-08 15:33:09',
            ),
            186 => 
            array (
                'id' => 232,
                'key' => 'add_tour_component_types',
                'table_name' => 'tour_component_types',
                'created_at' => '2020-12-08 15:33:09',
                'updated_at' => '2020-12-08 15:33:09',
            ),
            187 => 
            array (
                'id' => 233,
                'key' => 'delete_tour_component_types',
                'table_name' => 'tour_component_types',
                'created_at' => '2020-12-08 15:33:09',
                'updated_at' => '2020-12-08 15:33:09',
            ),
        ));
        
        
    }
}