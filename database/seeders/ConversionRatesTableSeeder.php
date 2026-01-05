<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ConversionRatesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('conversion_rates')->delete();
        
        \DB::table('conversion_rates')->insert(array (
            0 => 
            array (
                'id' => 1,
                'from_currency_id' => 83,
                'to_currency_id' => 16,
                'rate' => '1.34',
                'automatic' => 0,
                'changed' => '0000-00-00 00:00:00',
                'created_at' => '2026-01-05 10:10:48',
                'updated_at' => '2026-01-05 10:10:48',
            ),
            1 => 
            array (
                'id' => 2,
                'from_currency_id' => 16,
                'to_currency_id' => 83,
                'rate' => '0.75',
                'automatic' => 0,
                'changed' => '0000-00-00 00:00:00',
                'created_at' => '2026-01-05 10:10:48',
                'updated_at' => '2026-01-05 10:10:48',
            ),
            2 => 
            array (
                'id' => 3,
                'from_currency_id' => 83,
                'to_currency_id' => 1,
                'rate' => '1.15',
                'automatic' => 0,
                'changed' => '0000-00-00 00:00:00',
                'created_at' => '2026-01-05 10:11:19',
                'updated_at' => '2026-01-05 10:11:19',
            ),
            3 => 
            array (
                'id' => 4,
                'from_currency_id' => 1,
                'to_currency_id' => 83,
                'rate' => '0.87',
                'automatic' => 0,
                'changed' => '0000-00-00 00:00:00',
                'created_at' => '2026-01-05 10:11:19',
                'updated_at' => '2026-01-05 10:11:19',
            ),
            4 => 
            array (
                'id' => 5,
                'from_currency_id' => 83,
                'to_currency_id' => 2,
                'rate' => '2.00',
                'automatic' => 0,
                'changed' => '0000-00-00 00:00:00',
                'created_at' => '2026-01-05 10:11:39',
                'updated_at' => '2026-01-05 10:11:39',
            ),
            5 => 
            array (
                'id' => 6,
                'from_currency_id' => 2,
                'to_currency_id' => 83,
                'rate' => '0.50',
                'automatic' => 0,
                'changed' => '0000-00-00 00:00:00',
                'created_at' => '2026-01-05 10:11:39',
                'updated_at' => '2026-01-05 10:11:39',
            ),
            6 => 
            array (
                'id' => 7,
                'from_currency_id' => 2,
                'to_currency_id' => 16,
                'rate' => '0.67',
                'automatic' => 0,
                'changed' => '0000-00-00 00:00:00',
                'created_at' => '2026-01-05 10:12:12',
                'updated_at' => '2026-01-05 10:12:12',
            ),
            7 => 
            array (
                'id' => 8,
                'from_currency_id' => 16,
                'to_currency_id' => 2,
                'rate' => '1.50',
                'automatic' => 0,
                'changed' => '0000-00-00 00:00:00',
                'created_at' => '2026-01-05 10:12:12',
                'updated_at' => '2026-01-05 10:12:12',
            ),
            8 => 
            array (
                'id' => 9,
                'from_currency_id' => 2,
                'to_currency_id' => 1,
                'rate' => '0.57',
                'automatic' => 0,
                'changed' => '0000-00-00 00:00:00',
                'created_at' => '2026-01-05 10:12:29',
                'updated_at' => '2026-01-05 10:12:29',
            ),
            9 => 
            array (
                'id' => 10,
                'from_currency_id' => 1,
                'to_currency_id' => 2,
                'rate' => '1.76',
                'automatic' => 0,
                'changed' => '0000-00-00 00:00:00',
                'created_at' => '2026-01-05 10:12:29',
                'updated_at' => '2026-01-05 10:12:29',
            ),
            10 => 
            array (
                'id' => 11,
                'from_currency_id' => 16,
                'to_currency_id' => 1,
                'rate' => '0.86',
                'automatic' => 0,
                'changed' => '0000-00-00 00:00:00',
                'created_at' => '2026-01-05 10:12:51',
                'updated_at' => '2026-01-05 10:12:51',
            ),
            11 => 
            array (
                'id' => 12,
                'from_currency_id' => 1,
                'to_currency_id' => 16,
                'rate' => '1.17',
                'automatic' => 0,
                'changed' => '0000-00-00 00:00:00',
                'created_at' => '2026-01-05 10:12:51',
                'updated_at' => '2026-01-05 10:12:51',
            ),
        ));
        
        
    }
}