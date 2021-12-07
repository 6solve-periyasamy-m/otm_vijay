<?php

namespace Database\Seeders;

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
            14 => 
            array (
                'key' => 'email.payment.due',
                'value' => '<p>Dear [TITLE] [FIRST_NAME] [LAST_NAME],</p>

<p>You have a payment due on [DUE_PAYMENT_DATE], for [DUE_PAYMENT_AMOUNT]. This payment will need to be paid before this date, or your reservation may be at stake.</p>

<p>If you have any issues, please contact us and we can work towards a solution.</p>

<p>With thanks!</p>

<p>[SETTING_COMPANY_NAME]</p>',
            ),
            15 => 
            array (
                'key' => 'email.payment.made',
                'value' => '<p>Dear [TITLE] [FIRST_NAME] [LAST_NAME],</p>

<p>This is an email to inform you that we have recieved a payment of [PAYMENT_AMOUNT], as of [PAYMENT_DATE]. This payment has been made using [PAYMENT_METHOD], and should be shown in your account within 7 working days.</p>

<p>If any issues occur with this payment, we will be in touch to resolve the issue.</p>

<p>With thanks!</p>

<p>[SETTING_COMPANY_NAME]</p>',
            ),
            16 => 
            array (
                'key' => 'email.refund.given',
                'value' => '<p>Dear [TITLE] [FIRST_NAME] [LAST_NAME],</p>

<p>This is an email to inform you that you have recieved a refund of [PAYMENT_AMOUNT], as of [PAYMENT_DATE]. This will be paid back to you using [PAYMENT_METHOD], and should be with you within 7 working days.</p>

<p>If you do not recieve this refund within 20 working days, please contact us and we will help to resolve this issue.</p>

<p>With thanks!</p>

<p>[SETTING_COMPANY_NAME]</p>',
            ),
            17 =>
            array (
                'key' => 'system.format.date',
                'value' => 'd/m/Y',
            ),
            18 =>
            array (
                'key' => 'system.format.time',
                'value' => 'H:i',
            ),
        ));
        
        
    }
}
