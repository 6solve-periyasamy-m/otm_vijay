<?php

namespace App\Mail\Storage;

use Auth;

class SettingsMail extends TemplatedMail
{
    public function getShortcodes($model = null): array
    {
        return [
            'SETTING_COMPANY_NAME' => setting('company.name'),
            'SETTING_LOGO_URL' => asset(setting('company.logo')),
            'SETTING_COMPANY_ADDRESS_LINE_1' => setting('company.address.line_1'),
            'SETTING_COMPANY_ADDRESS_LINE_2' => setting('company.address.line_2'),
            'SETTING_COMPANY_ADDRESS_CITY' => setting('company.address.city'),
            'SETTING_COMPANY_ADDRESS_REGION' => setting('company.address.region'),
            'SETTING_COMPANY_ADDRESS_COUNTRY' => setting('company.address.country'),
            'SETTING_COMPANY_CONTACT_EMAIL' => setting('company.contact.email'),
            'SETTING_COMPANY_CONTACT_PHONE' => setting('company.contact.phone'),
            'SETTING_COMPANY_VAT' => setting('company.vat'),
            'SETTING_BOOKING_PREFIX' => setting('booking.prefix'),
            'SETTING_ATOL_ISSUER' => setting('atol.issuer'),
            'SETTING_ATOL_NUMBER' => setting('atol.number'),
            'SETTING_ATOL_STAMP' => asset(setting('atol.stamp')),
            'CURRENT_USER_NAME' => Auth::user()?->name ?? 'No User Found',
            'CURRENT_USER_EMAIL' => Auth::user()?->email ?? 'No User Found',
            'CURRENT_USER_IMAGE_URL' => Auth::user()?->avatar_url ?? 'No User Found',
        ];
    }

}
