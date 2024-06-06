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
                'key' => 'accommodation.lock',
                'value' => '0',
            ),
            1 => 
            array (
                'key' => 'accommodation.unlock',
                'value' => '0',
            ),
            2 => 
            array (
                'key' => 'activity.lock',
                'value' => '0',
            ),
            3 => 
            array (
                'key' => 'activity.unlock',
                'value' => '0',
            ),
            4 => 
            array (
                'key' => 'atol.enabled',
                'value' => '1',
            ),
            5 => 
            array (
                'key' => 'atol.filter',
                'value' => '244',
            ),
            6 => 
            array (
                'key' => 'atol.issuer',
                'value' => 'Octopus Travel Matrix',
            ),
            7 => 
            array (
                'key' => 'atol.number',
                'value' => '12345',
            ),
            8 => 
            array (
                'key' => 'atol.stamp',
                'value' => 'images/sample-atol.jpg',
            ),
            9 => 
            array (
                'key' => 'atol.year.start',
                'value' => '2022-04-01',
            ),
            10 => 
            array (
                'key' => 'authorization.reminders',
                'value' => '1660819442',
            ),
            11 => 
            array (
                'key' => 'booking.prefix',
                'value' => 'OTM',
            ),
            12 => 
            array (
                'key' => 'company.address.city',
                'value' => 'Walton',
            ),
            13 => 
            array (
                'key' => 'company.address.country',
                'value' => 'United Kingdom',
            ),
            14 => 
            array (
                'key' => 'company.address.line_1',
                'value' => '89 Ivy Lane',
            ),
            15 => 
            array (
                'key' => 'company.address.line_2',
                'value' => 'Colderson',
            ),
            16 => 
            array (
                'key' => 'company.address.postcode',
                'value' => 'ST15 5WN',
            ),
            17 => 
            array (
                'key' => 'company.address.region',
                'value' => 'Stockport',
            ),
            18 => 
            array (
                'key' => 'company.bank_transfer',
                'value' => '<p>OctopusTravelMatrix LTD&nbsp;<br>ABN: 11 222 333 444<br>BSB: 555-555&nbsp;<br>ACC: 666777&nbsp;<br>SWIFT: AAABBB8C<br>BANK: DemoData&nbsp;<br>BRANCH: Demo Branch</p>',
            ),
            19 => 
            array (
                'key' => 'company.contact.email',
                'value' => 'info@octopustravelmatrix.com',
            ),
            20 => 
            array (
                'key' => 'company.contact.phone',
                'value' => '01632960966',
            ),
            21 => 
            array (
                'key' => 'company.logo',
                'value' => 'images/octlogo.png',
            ),
            22 => 
            array (
                'key' => 'company.name',
                'value' => 'Octopus Travel Matrix Ltd.',
            ),
            23 => 
            array (
                'key' => 'company.url',
                'value' => 'https://octopustravelmatrix.com',
            ),
            24 => 
            array (
                'key' => 'company.vat',
                'value' => '6210102',
            ),
            25 => 
            array (
                'key' => 'components.lock',
                'value' => '0',
            ),
            26 => 
            array (
                'key' => 'email.additional-traveller-added.subject',
                'value' => 'You have been added as an additional traveller on [TOUR_NAME]',
            ),
            27 => 
            array (
                'key' => 'email.additional-traveller-added.template',
                'value' => '<p>Greetings [CUSTOMER_TITLE] [CUSTOMER_FIRST_NAME] [CUSTOMER_LAST_NAME],</p>

<p>This email is to inform you that you have been added to [TOUR_NAME] by [LEAD_TITLE] [LEAD_FIRST_NAME] [LEAD_LAST_NAME]. You will not have to do anything on your end, and this email is a courtesy.</p>

<p>If you believe this has been done in error, please don&#39;t hesitate to contact [LEAD_TITLE] [LEAD_FIRST_NAME] [LEAD_LAST_NAME], or, if you do not know this individual, please contact us at <a href="mailto://[SETTING_COMPANY_CONTACT_EMAIL]">[SETTING_COMPANY_CONTACT_EMAIL]</a>, and we will contact the lead booker to get this sorted!</p>

<p>With thanks!</p>

<p>[SETTING_COMPANY_NAME]</p>',
            ),
            28 => 
            array (
                'key' => 'email.additional-traveller-removed.subject',
                'value' => 'You have been removed as an additional traveller on [TOUR_NAME]',
            ),
            29 => 
            array (
                'key' => 'email.additional-traveller-removed.template',
                'value' => '<p>Greetings [CUSTOMER_TITLE] [CUSTOMER_FIRST_NAME] [CUSTOMER_LAST_NAME],</p>

<p>This email is to inform you that you have been removed from the [TOUR_NAME] tour by [LEAD_TITLE] [LEAD_FIRST_NAME] [LEAD_LAST_NAME]. You will not have to do anything on your end, and this email is a courtesy.</p>

<p>If you believe this has been done in error, please don&#39;t hesitate to contact [LEAD_TITLE] [LEAD_FIRST_NAME] [LEAD_LAST_NAME], or, if you do not know this individual, please contact us at <a href="mailto://[SETTING_COMPANY_CONTACT_EMAIL]">[SETTING_COMPANY_CONTACT_EMAIL]</a>, and we will contact the lead booker to get this sorted!</p>

<p>With thanks!</p>

<p>[SETTING_COMPANY_NAME]</p>',
            ),
            30 => 
            array (
                'key' => 'email.booking-confirmation.subject',
                'value' => 'Booking confirmed for Order [BOOKING_REFERENCE] on Tour [TOUR_NAME]',
            ),
            31 => 
            array (
                'key' => 'email.booking-confirmation.template',
                'value' => '<p>Greetings [LEAD_TITLE] [LEAD_FIRST_NAME] [LEAD_LAST_NAME],</p>

<p>Congratulations on your booking for the tour [TOUR_NAME]. This order is referenced with the booking code [BOOKING_REFERENCE]. To find out more, or make payments, go to our <a href="[PORTAL_LINK]">portal</a>.</p>

<p>Thank you, and enjoy your tour!</p>

<p>[SETTING_COMPANY_NAME]</p>',
            ),
            32 => 
            array (
                'key' => 'email.order-cancelled.subject',
                'value' => 'Order [BOOKING_REFERENCE]: Your order has been cancelled',
            ),
            33 => 
            array (
                'key' => 'email.order-cancelled.template',
                'value' => '<p>Greetings [LEAD_TITLE] [LEAD_FIRST_NAME] [LEAD_LAST_NAME],</p>

<p>This email is to let you know that your order [BOOKING_REFERENCE] to go on our [TOUR_NAME] tour has been cancelled. If you believe this to have been done in error, please contact us at <a href="mailto://[SETTING_COMPANY_EMAIL]">[SETTING_COMPANY_EMAIL]</a>.</p>

<p>Hoping to see you again soon!</p>

<p>[SETTING_COMPANY_NAME]</p>',
            ),
            34 => 
            array (
                'key' => 'email.order-changed.subject',
                'value' => 'Order [BOOKING_REFERENCE]: A change has been made to your order',
            ),
            35 => 
            array (
                'key' => 'email.order-changed.template',
                'value' => '<p>Greetings [LEAD_TITLE] [LEAD_FIRST_NAME] [LEAD_LAST_NAME],</p>

<p>This email is to inform you that some part of your order has changed, and more details can be found on our <a href="[PORTAL_LINK]">portal</a>. If you require assistance, please do not hesitate to contact us at <a href="mailto://[SETTING_COMPANY_CONTACT_EMAIL]">[SETTING_COMPANY_CONTACT_EMAIL]</a>, and we will be happy to help you where we can!</p>

<p>With thanks!</p>

<p>[SETTING_COMPANY_NAME]</p>',
            ),
            36 => 
            array (
                'key' => 'email.payment-due.subject',
                'value' => 'Order [BOOKING_REFERENCE]: Payment due on [DUE_PAYMENT_DATE]',
            ),
            37 => 
            array (
                'key' => 'email.payment-due.template',
                'value' => '<p>Dear [LEAD_TITLE] [LEAD_FIRST_NAME] [LEAD_LAST_NAME],</p>

<p>You have a payment due on [DUE_PAYMENT_DATE], for [DUE_PAYMENT_AMOUNT]. This payment will need to be paid before this date, or your reservation may be at stake.</p>

<p>If you have any issues, please contact us and we can work towards a solution.</p>

<p>With thanks!</p>

<p>[SETTING_COMPANY_NAME]</p>',
            ),
            38 => 
            array (
                'key' => 'email.payment-made.subject',
                'value' => 'Order [BOOKING_REFERENCE]: Payment accepted',
            ),
            39 => 
            array (
                'key' => 'email.payment-made.template',
                'value' => '<p>Dear [LEAD_TITLE] [LEAD_FIRST_NAME] [LEAD_LAST_NAME],</p>

<p>This is an email to inform you that we have recieved a payment of [PAYMENT_AMOUNT], as of [PAYMENT_DATE]. This payment has been made using [PAYMENT_METHOD], and should be shown in your account within 7 working days.</p>

<p>If any issues occur with this payment, we will be in touch to resolve the issue.</p>

<p>With thanks!</p>

<p>[SETTING_COMPANY_NAME]</p>',
            ),
            40 => 
            array (
                'key' => 'email.payment-overdue.subject',
                'value' => 'Order [BOOKING_REFERENCE]: Payment overdue since [DUE_PAYMENT_DATE]',
            ),
            41 => 
            array (
                'key' => 'email.payment-overdue.template',
                'value' => '<p>Dear [LEAD_TITLE] [LEAD_FIRST_NAME] [LEAD_LAST_NAME],</p>

<p>You have a payment due on [DUE_PAYMENT_DATE], for [DUE_PAYMENT_AMOUNT]. This payment is now overdue, and if you do not pay soon, then your order will be camcelled.</p>

<p>If you have any issues, please contact us and we can work towards a solution.</p>

<p>With thanks!</p>

<p>[SETTING_COMPANY_NAME]</p>',
            ),
            42 => 
            array (
                'key' => 'email.refund-given.subject',
                'value' => 'Order [BOOKING_REFERENCE]: Refund given',
            ),
            43 => 
            array (
                'key' => 'email.refund-given.template',
                'value' => '<p>Dear [LEAD_TITLE] [LEAD_FIRST_NAME] [LEAD_LAST_NAME],</p>

<p>This is an email to inform you that you have recieved a refund of [PAYMENT_AMOUNT], as of [PAYMENT_DATE]. This will be paid back to you using [PAYMENT_METHOD], and should be with you within 7 working days.</p>

<p>If you do not recieve this refund within 20 working days, please contact us and we will help to resolve this issue.</p>

<p>With thanks!</p>

<p>[SETTING_COMPANY_NAME]</p>',
            ),
            44 => 
            array (
                'key' => 'flight.lock',
                'value' => '0',
            ),
            45 => 
            array (
                'key' => 'flight.unlock',
                'value' => '0',
            ),
            46 => 
            array (
                'key' => 'installments.force',
                'value' => '0',
            ),
            47 => 
            array (
                'key' => 'invoice.style',
                'value' => '1',
            ),
            48 => 
            array (
                'key' => 'order-notes.lock',
                'value' => '0',
            ),
            49 => 
            array (
                'key' => 'order-notes.unlock',
                'value' => '0',
            ),
            50 => 
            array (
                'key' => 'passport.lock',
                'value' => '0',
            ),
            51 => 
            array (
                'key' => 'passport.unlock',
                'value' => '0',
            ),
            52 => 
            array (
                'key' => 'payment.required',
                'value' => '1',
            ),
            53 => 
            array (
                'key' => 'quote.prefix',
                'value' => 'OTMQ',
            ),
            54 => 
            array (
                'key' => 'quote.style',
                'value' => '1',
            ),
            55 => 
            array (
                'key' => 'social.facebook',
                'value' => 'https://www.facebook.com/OctopusTravelMatrix',
            ),
            56 => 
            array (
                'key' => 'system.currency',
                'value' => 'GBP',
            ),
            57 => 
            array (
                'key' => 'system.format.date',
                'value' => 'd/m/Y',
            ),
            58 => 
            array (
                'key' => 'system.format.time',
                'value' => 'H:i',
            ),
            59 => 
            array (
                'key' => 'system.historic',
                'value' => '6',
            ),
            60 => 
            array (
                'key' => 'system.installments.default',
                'value' => '{"180":15,"150":15,"120":15,"90":15}',
            ),
            61 => 
            array (
                'key' => 'system.installments.deposit',
                'value' => '10',
            ),
            62 => 
            array (
                'key' => 'system.installments.final',
                'value' => '60',
            ),
            63 => 
            array (
                'key' => 'system.mail.enabled',
                'value' => '1',
            ),
            64 => 
            array (
                'key' => 'system.year.start',
                'value' => '2022-04-01',
            ),
            65 => 
            array (
                'key' => 'transport.lock',
                'value' => '0',
            ),
            66 => 
            array (
                'key' => 'transport.unlock',
                'value' => '0',
            ),
        ));
        
        
    }
}