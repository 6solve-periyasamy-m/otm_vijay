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
        ));
        
        
    }
}