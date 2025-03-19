<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddMailTemplateToSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            [
                'key' => 'email.reservation-invoice-document.subject',
                'value' => 'Reservation - [BOOKING_REFERENCE] | [LEAD_FIRST_NAME] [LEAD_LAST_NAME]'
            ],
            [
                'key' => 'email.reservation-invoice-document.template',
                'value' => '<p>Greetings [LEAD_TITLE] [LEAD_FIRST_NAME] [LEAD_LAST_NAME],</p>
<p>Congratulations on your order for the [EVENT_NAME] [EVENT_TYPE]. This order is referenced with the booking code [BOOKING_REFERENCE]. We are pleased to confirm that your order has been successfully received and processed. </p>
<p>Please find your Booking Confirmation and Tax Invoice for your reference. <br></p>
<p>Thank you, and enjoy your tour! <br><br>&nbsp;</p>
<p>Kind regards,</p>
<p>[CURRENT_USER_NAME]</p>
<p style=""><strong>[SETTING_COMPANY_NAME]</strong></p>
<p><span style="color:#f35b15"><strong>P</strong></span>: [SETTING_COMPANY_CONTACT_PHONE]</p>
<p><span style="color:#f35b15"><strong>E</strong></span>: [CURRENT_USER_EMAIL]</p>
<p><span style="color:#f35b15"><strong>A</strong></span>: [SETTING_COMPANY_ADDRESS_LINE_1],[SETTING_COMPANY_ADDRESS_LINE_2],[SETTING_COMPANY_ADDRESS_CITY],[SETTING_COMPANY_ADDRESS_REGION],[SETTING_COMPANY_ADDRESS_COUNTRY]</p>
<p><span style="color:#f35b15"><strong>W</strong></span>:[SETTING_COMPANY_URL]</p>
<p><img src="[SETTING_LOGO_URL]"></p>'
            ],
        ]);
    }
}
