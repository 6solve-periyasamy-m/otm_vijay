@extends('layout.form', ['action' => route('settings.update'), 'multipart' => true,])

@section('title', 'Edit Settings')

@section('form-body')
@include('partials.fields.text', ['name' => 'Company Name', 'field' => 'company_name', 'value' => \App\Repository\SettingsRepository::getOrDefault('company.name', ''), 'width' => 6])
@include('partials.fields.text', ['name' => 'Company Email', 'field' => 'company_email', 'value' => \App\Repository\SettingsRepository::getOrDefault('company.contact.email', ''), 'width' => 6])
@include('partials.fields.text', ['name' => 'Company Phone Number', 'field' => 'company_phone', 'value' => \App\Repository\SettingsRepository::getOrDefault('company.contact.phone', ''), 'width' => 6])
@include('partials.fields.text', ['name' => 'Company VAT', 'field' => 'company_vat', 'value' => \App\Repository\SettingsRepository::getOrDefault('company.vat', ''), 'width' => 6])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
@include('partials.fields.text', ['name' => 'Company Address Line 1', 'field' => 'address_line_1', 'value' => \App\Repository\SettingsRepository::getOrDefault('company.address.line_1', ''), 'width' => 6])
@include('partials.fields.text', ['name' => 'Company Address Line 2', 'field' => 'address_line_2', 'value' => \App\Repository\SettingsRepository::getOrDefault('company.address.line_2', ''), 'width' => 6])
@include('partials.fields.text', ['name' => 'Company Address City', 'field' => 'city', 'value' => \App\Repository\SettingsRepository::getOrDefault('company.address.city', ''), 'width' => 4])
@include('partials.fields.text', ['name' => 'Company Address Region', 'field' => 'region', 'value' => \App\Repository\SettingsRepository::getOrDefault('company.address.region', ''), 'width' => 4])
@include('partials.fields.text', ['name' => 'Company Address Postcode', 'field' => 'postcode', 'value' => \App\Repository\SettingsRepository::getOrDefault('company.address.postcode', ''), 'width' => 4])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
@include('partials.fields.text', ['name' => 'Booking Reference Prefix', 'field' => 'booking_prefix', 'value' => \App\Repository\SettingsRepository::getOrDefault('booking.prefix', ''), 'width' => 4])
@include('partials.fields.text', ['name' => 'ATOL Issuer', 'field' => 'atol_issuer', 'value' => \App\Repository\SettingsRepository::getOrDefault('atol.issuer', ''), 'width' => 4])
@include('partials.fields.text', ['name' => 'ATOL Number', 'field' => 'atol_number', 'value' => \App\Repository\SettingsRepository::getOrDefault('atol.number', ''), 'width' => 4])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
@include('partials.fields.file', ['name' => 'Company Logo', 'field' => 'company_logo', 'width' => 6])
@include('partials.fields.file', ['name' => 'ATOL Stamp', 'field' => 'atol_stamp', 'width' => 6])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
@include('partials.fields.selector.default',
    ['name' => 'System Currency', 'field' => 'currency_id', 'value' => \App\Repository\LocationsRepository::getCurrencyIdByCode(\App\Repository\SettingsRepository::getOrDefault('system.currency', '')) ?? null, 'route' => 'currencies', 'width' => 6,])
@include('partials.fields.text', ['name' => 'Stripe Key', 'field' => 'stripe_key', 'value' => \App\Repository\SettingsRepository::getOrDefault('billing.stripe.key', ''), 'width' => 6])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
@include('partials.fields.dropdown', [
    'name' => 'Date Format',
    'field' => 'date_format',
    'values' => [
        'd/m/Y' => '31/01/2021 (Time)',
        'm/d/Y' => '01/31/2021 (Time)',
        'Y/m/d' => '2021/01/31 (Time)',
        'd/M/Y' => '31/Jan/2021 (Time)',
        'M/d/Y' => 'Jan/31/2021 (Time)',
        'Y/M/d' => '2021/Jan/31 (Time)',
        'd-m-Y' => '31-01-2021 (Time)',
        'm-d-Y' => '01-31-2021 (Time)',
        'Y-m-d' => '2021-01-31 (Time)',
        'd-M-Y' => '31-Jan-2021 (Time)',
        'M-d-Y' => 'Jan-31-2021 (Time)',
        'Y-M-d' => '2021-Jan-31 (Time)',
        'dS F Y -' => '31st January 2021 - (Time)',
        'F dS Y -' => 'January 31st 2021 - (Time)'
    ],
    'selected' => \App\Repository\SettingsRepository::getOrDefault('system.format.date', 'd/m/Y'),
    'width' => 6,
])
@include('partials.fields.dropdown', [
    'name' => 'Time Format',
    'field' => 'time_format',
    'values' => [
        'H:i' => '14:30',
        'h:i A' => '02:30 PM',
        'H:i:s' => '14:30:45',
        'h:i:s A'=> '02:30:45 PM'
    ],
    'selected' => \App\Repository\SettingsRepository::getOrDefault('system.format.date', 'H:i'),
    'width' => 6,
])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
@include('partials.fields.submit')
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
@include('partials.fields.button', ['name' => 'Edit Booking Email', 'route' => route('email.booking.edit'), 'width' => 2, 'color' => 'amber'])
@include('partials.fields.button', ['name' => 'Edit Payment Due Email', 'route' => route('email.payment-due.edit'), 'width' => 2, 'color' => 'amber'])
@include('partials.fields.button', ['name' => 'Edit Payment Received Email', 'route' => route('email.payment-made.edit'), 'width' => 2, 'color' => 'amber'])
@include('partials.fields.button', ['name' => 'Edit Refund Given Email', 'route' => route('email.refund-given.edit'), 'width' => 2, 'color' => 'amber'])
@endsection
