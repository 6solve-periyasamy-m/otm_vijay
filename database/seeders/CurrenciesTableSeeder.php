<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('currencies')->delete();
        
        \DB::table('currencies')->insert(array (
            0 => 
            array (
                'id' => 1,
                'code' => 'EUR',
                'name' => 'Euro',
                'symbol' => '€',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'code' => 'AUD',
                'name' => 'Australian dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'code' => 'KID',
                'name' => 'Kiribati dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'code' => 'XOF',
                'name' => 'West African CFA franc',
                'symbol' => 'Fr',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'code' => 'LBP',
                'name' => 'Lebanese pound',
                'symbol' => 'ل.ل',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'code' => 'JMD',
                'name' => 'Jamaican dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'code' => 'FKP',
                'name' => 'Falkland Islands pound',
                'symbol' => '£',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'code' => 'KMF',
                'name' => 'Comorian franc',
                'symbol' => 'Fr',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'code' => 'ZWL',
                'name' => 'Zimbabwean dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'code' => 'XAF',
                'name' => 'Central African CFA franc',
                'symbol' => 'Fr',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'code' => 'NZD',
                'name' => 'New Zealand dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'code' => 'ETB',
                'name' => 'Ethiopian birr',
                'symbol' => 'Br',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'code' => 'XPF',
                'name' => 'CFP franc',
                'symbol' => '₣',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'code' => 'XCD',
                'name' => 'Eastern Caribbean dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'code' => 'GIP',
                'name' => 'Gibraltar pound',
                'symbol' => '£',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'code' => 'USD',
                'name' => 'United States dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'code' => 'SHP',
                'name' => 'Saint Helena pound',
                'symbol' => '£',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'code' => 'CAD',
                'name' => 'Canadian dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'code' => 'KES',
                'name' => 'Kenyan shilling',
                'symbol' => 'Sh',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'code' => 'CVE',
                'name' => 'Cape Verdean escudo',
                'symbol' => 'Esc',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'code' => 'CKD',
                'name' => 'Cook Islands dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'code' => 'GEL',
                'name' => 'lari',
                'symbol' => '₾',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'code' => 'SBD',
                'name' => 'Solomon Islands dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'code' => 'CNY',
                'name' => 'Chinese yuan',
                'symbol' => '¥',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'code' => 'RWF',
                'name' => 'Rwandan franc',
                'symbol' => 'Fr',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'code' => 'BYN',
                'name' => 'Belarusian ruble',
                'symbol' => 'Br',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'code' => 'PKR',
                'name' => 'Pakistani rupee',
                'symbol' => '₨',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'code' => 'UYU',
                'name' => 'Uruguayan peso',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'code' => 'ERN',
                'name' => 'Eritrean nakfa',
                'symbol' => 'Nfk',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'code' => 'ZAR',
                'name' => 'South African rand',
                'symbol' => 'R',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'code' => 'ZMW',
                'name' => 'Zambian kwacha',
                'symbol' => 'ZK',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'code' => 'LAK',
                'name' => 'Lao kip',
                'symbol' => '₭',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'code' => 'LRD',
                'name' => 'Liberian dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'code' => 'UGX',
                'name' => 'Ugandan shilling',
                'symbol' => 'Sh',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'code' => 'GTQ',
                'name' => 'Guatemalan quetzal',
                'symbol' => 'Q',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'code' => 'NOK',
                'name' => 'krone',
                'symbol' => 'kr',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'code' => 'MMK',
                'name' => 'Burmese kyat',
                'symbol' => 'Ks',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'code' => 'DOP',
                'name' => 'Dominican peso',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'code' => 'TJS',
                'name' => 'Tajikistani somoni',
                'symbol' => 'ЅМ',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'code' => 'VES',
                'name' => 'Venezuelan bolívar soberano',
                'symbol' => 'Bs.S.',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'code' => 'MDL',
                'name' => 'Moldovan leu',
                'symbol' => 'L',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'code' => 'SEK',
                'name' => 'Swedish krona',
                'symbol' => 'kr',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            42 => 
            array (
                'id' => 43,
                'code' => 'MVR',
                'name' => 'Maldivian rufiyaa',
                'symbol' => '.ރ',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            43 => 
            array (
                'id' => 44,
                'code' => 'GYD',
                'name' => 'Guyanese dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            44 => 
            array (
                'id' => 45,
                'code' => 'PHP',
                'name' => 'Philippine peso',
                'symbol' => '₱',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            45 => 
            array (
                'id' => 46,
                'code' => 'KHR',
                'name' => 'Cambodian riel',
                'symbol' => '៛',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            46 => 
            array (
                'id' => 47,
                'code' => 'MNT',
                'name' => 'Mongolian tögrög',
                'symbol' => '₮',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            47 => 
            array (
                'id' => 48,
                'code' => 'SYP',
                'name' => 'Syrian pound',
                'symbol' => '£',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            48 => 
            array (
                'id' => 49,
                'code' => 'ILS',
                'name' => 'Israeli new shekel',
                'symbol' => '₪',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            49 => 
            array (
                'id' => 50,
                'code' => 'MZN',
                'name' => 'Mozambican metical',
                'symbol' => 'MT',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            50 => 
            array (
                'id' => 51,
                'code' => 'AMD',
                'name' => 'Armenian dram',
                'symbol' => '֏',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            51 => 
            array (
                'id' => 52,
                'code' => 'WST',
                'name' => 'Samoan tālā',
                'symbol' => 'T',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            52 => 
            array (
                'id' => 53,
                'code' => 'FJD',
                'name' => 'Fijian dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            53 => 
            array (
                'id' => 54,
                'code' => 'MYR',
                'name' => 'Malaysian ringgit',
                'symbol' => 'RM',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            54 => 
            array (
                'id' => 55,
                'code' => 'TOP',
                'name' => 'Tongan paʻanga',
                'symbol' => 'T$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            55 => 
            array (
                'id' => 56,
                'code' => 'SRD',
                'name' => 'Surinamese dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            56 => 
            array (
                'id' => 57,
                'code' => 'BGN',
                'name' => 'Bulgarian lev',
                'symbol' => 'лв',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            57 => 
            array (
                'id' => 58,
                'code' => 'BRL',
                'name' => 'Brazilian real',
                'symbol' => 'R$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            58 => 
            array (
                'id' => 59,
                'code' => 'IDR',
                'name' => 'Indonesian rupiah',
                'symbol' => 'Rp',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            59 => 
            array (
                'id' => 60,
                'code' => 'TVD',
                'name' => 'Tuvaluan dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            60 => 
            array (
                'id' => 61,
                'code' => 'BMD',
                'name' => 'Bermudian dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            61 => 
            array (
                'id' => 62,
                'code' => 'DKK',
                'name' => 'Danish krone',
                'symbol' => 'kr',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            62 => 
            array (
                'id' => 63,
                'code' => 'FOK',
                'name' => 'Faroese króna',
                'symbol' => 'kr',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            63 => 
            array (
                'id' => 64,
                'code' => 'JPY',
                'name' => 'Japanese yen',
                'symbol' => '¥',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            64 => 
            array (
                'id' => 65,
                'code' => 'ISK',
                'name' => 'Icelandic króna',
                'symbol' => 'kr',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            65 => 
            array (
                'id' => 66,
                'code' => 'KGS',
                'name' => 'Kyrgyzstani som',
                'symbol' => 'с',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            66 => 
            array (
                'id' => 67,
                'code' => 'SZL',
                'name' => 'Swazi lilangeni',
                'symbol' => 'L',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            67 => 
            array (
                'id' => 68,
                'code' => 'BIF',
                'name' => 'Burundian franc',
                'symbol' => 'Fr',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            68 => 
            array (
                'id' => 69,
                'code' => 'TTD',
                'name' => 'Trinidad and Tobago dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            69 => 
            array (
                'id' => 70,
                'code' => 'JOD',
                'name' => 'Jordanian dinar',
                'symbol' => 'د.ا',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            70 => 
            array (
                'id' => 71,
                'code' => 'MAD',
                'name' => 'Moroccan dirham',
                'symbol' => 'د.م.',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            71 => 
            array (
                'id' => 72,
                'code' => 'EGP',
                'name' => 'Egyptian pound',
                'symbol' => 'E£',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            72 => 
            array (
                'id' => 73,
                'code' => 'COP',
                'name' => 'Colombian peso',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            73 => 
            array (
                'id' => 74,
                'code' => 'ANG',
                'name' => 'Netherlands Antillean guilder',
                'symbol' => 'ƒ',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            74 => 
            array (
                'id' => 75,
                'code' => 'BZD',
                'name' => 'Belize dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            75 => 
            array (
                'id' => 76,
                'code' => 'MUR',
                'name' => 'Mauritian rupee',
                'symbol' => '₨',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            76 => 
            array (
                'id' => 77,
                'code' => 'BTN',
                'name' => 'Bhutanese ngultrum',
                'symbol' => 'Nu.',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            77 => 
            array (
                'id' => 78,
                'code' => 'INR',
                'name' => 'Indian rupee',
                'symbol' => '₹',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            78 => 
            array (
                'id' => 79,
                'code' => 'DZD',
                'name' => 'Algerian dinar',
                'symbol' => 'دج',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            79 => 
            array (
                'id' => 80,
                'code' => 'MRU',
                'name' => 'Mauritanian ouguiya',
                'symbol' => 'UM',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            80 => 
            array (
                'id' => 81,
                'code' => 'DJF',
                'name' => 'Djiboutian franc',
                'symbol' => 'Fr',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            81 => 
            array (
                'id' => 82,
                'code' => 'GNF',
                'name' => 'Guinean franc',
                'symbol' => 'Fr',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            82 => 
            array (
                'id' => 83,
                'code' => 'GBP',
                'name' => 'British pound',
                'symbol' => '£',
                'created_at' => '2022-03-11 11:36:11',
                'updated_at' => '2022-03-11 11:36:11',
                'deleted_at' => NULL,
            ),
            83 => 
            array (
                'id' => 84,
                'code' => 'IMP',
                'name' => 'Manx pound',
                'symbol' => '£',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            84 => 
            array (
                'id' => 85,
                'code' => 'UAH',
                'name' => 'Ukrainian hryvnia',
                'symbol' => '₴',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            85 => 
            array (
                'id' => 86,
                'code' => 'PLN',
                'name' => 'Polish złoty',
                'symbol' => 'zł',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            86 => 
            array (
                'id' => 87,
                'code' => 'PAB',
                'name' => 'Panamanian balboa',
                'symbol' => 'B/.',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            87 => 
            array (
                'id' => 88,
                'code' => 'CLP',
                'name' => 'Chilean peso',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            88 => 
            array (
                'id' => 89,
                'code' => 'BWP',
                'name' => 'Botswana pula',
                'symbol' => 'P',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            89 => 
            array (
                'id' => 90,
                'code' => 'TMT',
                'name' => 'Turkmenistan manat',
                'symbol' => 'm',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            90 => 
            array (
                'id' => 91,
                'code' => 'MGA',
                'name' => 'Malagasy ariary',
                'symbol' => 'Ar',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            91 => 
            array (
                'id' => 92,
                'code' => 'CDF',
                'name' => 'Congolese franc',
                'symbol' => 'FC',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            92 => 
            array (
                'id' => 93,
                'code' => 'VND',
                'name' => 'Vietnamese đồng',
                'symbol' => '₫',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            93 => 
            array (
                'id' => 94,
                'code' => 'TRY',
                'name' => 'Turkish lira',
                'symbol' => '₺',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            94 => 
            array (
                'id' => 95,
                'code' => 'KZT',
                'name' => 'Kazakhstani tenge',
                'symbol' => '₸',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            95 => 
            array (
                'id' => 96,
                'code' => 'RON',
                'name' => 'Romanian leu',
                'symbol' => 'lei',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            96 => 
            array (
                'id' => 97,
                'code' => 'TND',
                'name' => 'Tunisian dinar',
                'symbol' => 'د.ت',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            97 => 
            array (
                'id' => 98,
                'code' => 'HUF',
                'name' => 'Hungarian forint',
                'symbol' => 'Ft',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            98 => 
            array (
                'id' => 99,
                'code' => 'UZS',
                'name' => 'Uzbekistani soʻm',
                'symbol' => 'so\'m',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            99 => 
            array (
                'id' => 100,
                'code' => 'RUB',
                'name' => 'Russian ruble',
                'symbol' => '₽',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            100 => 
            array (
                'id' => 101,
                'code' => 'GHS',
                'name' => 'Ghanaian cedi',
                'symbol' => '₵',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            101 => 
            array (
                'id' => 102,
                'code' => 'AWG',
                'name' => 'Aruban florin',
                'symbol' => 'ƒ',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            102 => 
            array (
                'id' => 103,
                'code' => 'AOA',
                'name' => 'Angolan kwanza',
                'symbol' => 'Kz',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            103 => 
            array (
                'id' => 104,
                'code' => 'CHF',
                'name' => 'Swiss franc',
                'symbol' => 'Fr.',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            104 => 
            array (
                'id' => 105,
                'code' => 'TWD',
                'name' => 'New Taiwan dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            105 => 
            array (
                'id' => 106,
                'code' => 'MKD',
                'name' => 'denar',
                'symbol' => 'den',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            106 => 
            array (
                'id' => 107,
                'code' => 'SLL',
                'name' => 'Sierra Leonean leone',
                'symbol' => 'Le',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            107 => 
            array (
                'id' => 108,
                'code' => 'CZK',
                'name' => 'Czech koruna',
                'symbol' => 'Kč',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            108 => 
            array (
                'id' => 109,
                'code' => 'PEN',
                'name' => 'Peruvian sol',
                'symbol' => 'S/ ',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            109 => 
            array (
                'id' => 110,
                'code' => 'BND',
                'name' => 'Brunei dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            110 => 
            array (
                'id' => 111,
                'code' => 'SGD',
                'name' => 'Singapore dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            111 => 
            array (
                'id' => 112,
                'code' => 'YER',
                'name' => 'Yemeni rial',
                'symbol' => '﷼',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            112 => 
            array (
                'id' => 113,
                'code' => 'HRK',
                'name' => 'Croatian kuna',
                'symbol' => 'kn',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            113 => 
            array (
                'id' => 114,
                'code' => 'LYD',
                'name' => 'Libyan dinar',
                'symbol' => 'ل.د',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            114 => 
            array (
                'id' => 115,
                'code' => 'PYG',
                'name' => 'Paraguayan guaraní',
                'symbol' => '₲',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            115 => 
            array (
                'id' => 116,
                'code' => 'LKR',
                'name' => 'Sri Lankan rupee',
                'symbol' => 'Rs  රු',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            116 => 
            array (
                'id' => 117,
                'code' => 'RSD',
                'name' => 'Serbian dinar',
                'symbol' => 'дин.',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            117 => 
            array (
                'id' => 118,
                'code' => 'GGP',
                'name' => 'Guernsey pound',
                'symbol' => '£',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            118 => 
            array (
                'id' => 119,
                'code' => 'JEP',
                'name' => 'Jersey pound',
                'symbol' => '£',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            119 => 
            array (
                'id' => 120,
                'code' => 'KPW',
                'name' => 'North Korean won',
                'symbol' => '₩',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            120 => 
            array (
                'id' => 121,
                'code' => 'SOS',
                'name' => 'Somali shilling',
                'symbol' => 'Sh',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            121 => 
            array (
                'id' => 122,
                'code' => 'SDG',
                'name' => 'Sudanese pound',
                'symbol' => NULL,
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            122 => 
            array (
                'id' => 123,
                'code' => 'NGN',
                'name' => 'Nigerian naira',
                'symbol' => '₦',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            123 => 
            array (
                'id' => 124,
                'code' => 'ALL',
                'name' => 'Albanian lek',
                'symbol' => 'L',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            124 => 
            array (
                'id' => 125,
                'code' => 'BOB',
                'name' => 'Bolivian boliviano',
                'symbol' => 'Bs.',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            125 => 
            array (
                'id' => 126,
                'code' => 'PGK',
                'name' => 'Papua New Guinean kina',
                'symbol' => 'K',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            126 => 
            array (
                'id' => 127,
                'code' => 'QAR',
                'name' => 'Qatari riyal',
                'symbol' => 'ر.ق',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            127 => 
            array (
                'id' => 128,
                'code' => 'BBD',
                'name' => 'Barbadian dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            128 => 
            array (
                'id' => 129,
                'code' => 'CRC',
                'name' => 'Costa Rican colón',
                'symbol' => '₡',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            129 => 
            array (
                'id' => 130,
                'code' => 'SCR',
                'name' => 'Seychellois rupee',
                'symbol' => '₨',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            130 => 
            array (
                'id' => 131,
                'code' => 'HKD',
                'name' => 'Hong Kong dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            131 => 
            array (
                'id' => 132,
                'code' => 'AFN',
                'name' => 'Afghan afghani',
                'symbol' => '؋',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            132 => 
            array (
                'id' => 133,
                'code' => 'NIO',
                'name' => 'Nicaraguan córdoba',
                'symbol' => 'C$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            133 => 
            array (
                'id' => 134,
                'code' => 'AED',
                'name' => 'United Arab Emirates dirham',
                'symbol' => 'د.إ',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            134 => 
            array (
                'id' => 135,
                'code' => 'IRR',
                'name' => 'Iranian rial',
                'symbol' => '﷼',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            135 => 
            array (
                'id' => 136,
                'code' => 'ARS',
                'name' => 'Argentine peso',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            136 => 
            array (
                'id' => 137,
                'code' => 'MXN',
                'name' => 'Mexican peso',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            137 => 
            array (
                'id' => 138,
                'code' => 'AZN',
                'name' => 'Azerbaijani manat',
                'symbol' => '₼',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            138 => 
            array (
                'id' => 139,
                'code' => 'HNL',
                'name' => 'Honduran lempira',
                'symbol' => 'L',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            139 => 
            array (
                'id' => 140,
                'code' => 'TZS',
                'name' => 'Tanzanian shilling',
                'symbol' => 'Sh',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            140 => 
            array (
                'id' => 141,
                'code' => 'IQD',
                'name' => 'Iraqi dinar',
                'symbol' => 'ع.د',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            141 => 
            array (
                'id' => 142,
                'code' => 'BHD',
                'name' => 'Bahraini dinar',
                'symbol' => '.د.ب',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            142 => 
            array (
                'id' => 143,
                'code' => 'KRW',
                'name' => 'South Korean won',
                'symbol' => '₩',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            143 => 
            array (
                'id' => 144,
                'code' => 'KYD',
                'name' => 'Cayman Islands dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            144 => 
            array (
                'id' => 145,
                'code' => 'LSL',
                'name' => 'Lesotho loti',
                'symbol' => 'L',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            145 => 
            array (
                'id' => 146,
                'code' => 'NPR',
                'name' => 'Nepalese rupee',
                'symbol' => '₨',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            146 => 
            array (
                'id' => 147,
                'code' => 'OMR',
                'name' => 'Omani rial',
                'symbol' => 'ر.ع.',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            147 => 
            array (
                'id' => 148,
                'code' => 'BAM',
                'name' => 'Bosnia and Herzegovina convertible mark',
                'symbol' => NULL,
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            148 => 
            array (
                'id' => 149,
                'code' => 'STN',
                'name' => 'São Tomé and Príncipe dobra',
                'symbol' => 'Db',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            149 => 
            array (
                'id' => 150,
                'code' => 'BSD',
                'name' => 'Bahamian dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            150 => 
            array (
                'id' => 151,
                'code' => 'NAD',
                'name' => 'Namibian dollar',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            151 => 
            array (
                'id' => 152,
                'code' => 'VUV',
                'name' => 'Vanuatu vatu',
                'symbol' => 'Vt',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            152 => 
            array (
                'id' => 153,
                'code' => 'BDT',
                'name' => 'Bangladeshi taka',
                'symbol' => '৳',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            153 => 
            array (
                'id' => 154,
                'code' => 'GMD',
                'name' => 'dalasi',
                'symbol' => 'D',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            154 => 
            array (
                'id' => 155,
                'code' => 'MOP',
                'name' => 'Macanese pataca',
                'symbol' => 'P',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            155 => 
            array (
                'id' => 156,
                'code' => 'CUC',
                'name' => 'Cuban convertible peso',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            156 => 
            array (
                'id' => 157,
                'code' => 'CUP',
                'name' => 'Cuban peso',
                'symbol' => '$',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            157 => 
            array (
                'id' => 158,
                'code' => 'SSP',
                'name' => 'South Sudanese pound',
                'symbol' => '£',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            158 => 
            array (
                'id' => 159,
                'code' => 'MWK',
                'name' => 'Malawian kwacha',
                'symbol' => 'MK',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            159 => 
            array (
                'id' => 160,
                'code' => 'HTG',
                'name' => 'Haitian gourde',
                'symbol' => 'G',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            160 => 
            array (
                'id' => 161,
                'code' => 'SAR',
                'name' => 'Saudi riyal',
                'symbol' => 'ر.س',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            161 => 
            array (
                'id' => 162,
                'code' => 'KWD',
                'name' => 'Kuwaiti dinar',
                'symbol' => 'د.ك',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
            162 => 
            array (
                'id' => 163,
                'code' => 'THB',
                'name' => 'Thai baht',
                'symbol' => '฿',
                'created_at' => '2022-03-11 11:36:12',
                'updated_at' => '2022-03-11 11:36:12',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}