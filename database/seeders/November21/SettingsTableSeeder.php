<?php

namespace Database\Seeders\November21;

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'key' => 'atol.issuer',
                'value' => 'Octopus Travel Matrix',
            ),
            1 => 
            array (
                'key' => 'atol.number',
                'value' => '12345',
            ),
            2 => 
            array (
                'key' => 'atol.stamp',
                'value' => 'images/sample-atol.jpg',
            ),
            3 => 
            array (
                'key' => 'booking.prefix',
                'value' => 'OTM',
            ),
            4 => 
            array (
                'key' => 'company.address.city',
                'value' => 'Walton',
            ),
            5 => 
            array (
                'key' => 'company.address.line_1',
                'value' => '89 Ivy Lane',
            ),
            6 => 
            array (
                'key' => 'company.address.line_2',
                'value' => 'Colderson',
            ),
            7 => 
            array (
                'key' => 'company.address.postcode',
                'value' => 'ST15 5WN',
            ),
            8 => 
            array (
                'key' => 'company.address.region',
                'value' => 'Stockport',
            ),
            9 => 
            array (
                'key' => 'company.contact.email',
                'value' => 'info@octopustravelmatrix.com',
            ),
            10 => 
            array (
                'key' => 'company.contact.phone',
                'value' => '01632960966',
            ),
            11 => 
            array (
                'key' => 'company.logo',
                'value' => 'images/octlogo.png',
            ),
            12 => 
            array (
                'key' => 'company.name',
                'value' => 'Octopus Travel Matrix Ltd.',
            ),
            13 => 
            array (
                'key' => 'company.vat',
                'value' => '6210102',
            ),
        ));
        
        
    }
}
