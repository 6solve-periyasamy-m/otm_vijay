@extends('layout.form', ['action' => route('settings.update'), 'multipart' => true,])

@section('title', 'Edit Settings')

@section('form-body')
    @include('partials.fields.text', ['name' => 'Company Name', 'field' => 'company_name', 'value' => setting('company.name', ''), 'width' => 6])
    @include('partials.fields.text', ['name' => 'Company Email', 'field' => 'company_email', 'value' => setting('company.contact.email', ''), 'width' => 6])
    @include('partials.fields.text', ['name' => 'Company Phone Number', 'field' => 'company_phone', 'value' => setting('company.contact.phone', ''), 'width' => 6])
    @include('partials.fields.text', ['name' => 'Company VAT', 'field' => 'company_vat', 'value' => setting('company.vat', ''), 'width' => 6])
    @include('partials.fields.text', ['name' => 'Company Link', 'field' => 'company_url', 'value' => setting('company.url', ''), 'width' => 12])
    <hr class="splitter"/>
    @include('partials.fields.text', ['name' => 'Company Address Line 1', 'field' => 'address_line_1', 'value' => setting('company.address.line_1', ''), 'width' => 6])
    @include('partials.fields.text', ['name' => 'Company Address Line 2', 'field' => 'address_line_2', 'value' => setting('company.address.line_2', ''), 'width' => 6])
    @include('partials.fields.text', ['name' => 'Company Address City', 'field' => 'city', 'value' => setting('company.address.city', ''), 'width' => 6])
    @include('partials.fields.text', ['name' => 'Company Address Region', 'field' => 'region', 'value' => setting('company.address.region', ''), 'width' => 6])
    @include('partials.fields.text', ['name' => 'Company Address Country', 'field' => 'country', 'value' => setting('company.address.country', ''), 'width' => 6])
    @include('partials.fields.text', ['name' => 'Company Address Postcode', 'field' => 'postcode', 'value' => setting('company.address.postcode', ''), 'width' => 6])
    <hr class="splitter"/>
    @include('partials.fields.text', ['name' => 'Booking Reference Prefix', 'field' => 'booking_prefix', 'value' => setting('booking.prefix', ''), 'width' => 4])
    @include('partials.fields.text', ['name' => 'ATOL Issuer', 'field' => 'atol_issuer', 'value' => setting('atol.issuer', ''), 'width' => 4])
    @include('partials.fields.text', ['name' => 'ATOL Number', 'field' => 'atol_number', 'value' => setting('atol.number', ''), 'width' => 4])
    <hr class="splitter"/>
    @include('partials.fields.file', ['name' => 'Company Logo', 'field' => 'company_logo', 'width' => 6])
    @include('partials.fields.file', ['name' => 'ATOL Stamp', 'field' => 'atol_stamp', 'width' => 6])
    <hr class="splitter"/>
    @include('partials.fields.selector.default',
        ['name' => 'System Currency', 'field' => 'currency_id', 'value' => \App\Repository\LocationsRepository::getCurrencyIdByCode(setting('system.currency', '')) ?? null, 'route' => 'currencies', 'width' => 6,])
    @include('partials.fields.checkbox', ['name' => 'Require Immediate Payment For Upgrades/Add-ons', 'field' => 'payment_required', 'value' => flag('payment.required', true), 'width' => 6,])
@include('partials.fields.text', ['name' => 'Booking Success Page', 'field' => 'booking_redirect', 'value' => setting('booking.success.redirect', ''), 'width' => 3,])
@include('partials.fields.text', ['name' => 'Installment Payment Success Page', 'field' => 'payment_redirect', 'value' => setting('payment.success.redirect', ''), 'width' => 3,])
@include('partials.fields.text', ['name' => 'Add-on Purchase Success Page', 'field' => 'addon_redirect', 'value' => setting('purchase.addon.success.redirect', ''), 'width' => 3,])
@include('partials.fields.text', ['name' => 'Upgrade Purchase Success Page', 'field' => 'upgrade_redirect', 'value' => setting('purchase.upgrade.success.redirect', ''), 'width' => 3,])
    <hr class="splitter"/>
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
            'jS F Y' => '31st January 2021 (Time)',
            'F jS Y' => 'January 31st 2021 (Time)'
        ],
        'selected' => setting('system.format.date', 'd/m/Y'),
        'width' => 4,
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
        'selected' => setting('system.format.date', 'H:i'),
        'width' => 4,
    ])
    @include('partials.fields.date', ['name' => 'Financial Year Start Date', 'field' => 'year_start', 'value' => setting('system.year.start', '2022-04-01'), 'width' => 4])
    <hr class="splitter"/>
    @include('partials.fields.text', ['name' => 'Facebook Link', 'field' => 'social_facebook', 'value' => setting('social.facebook', ''), 'width' => 4])
    @include('partials.fields.text', ['name' => 'Twitter Link', 'field' => 'social_twitter', 'value' => setting('social.twitter', ''), 'width' => 4])
    @include('partials.fields.text', ['name' => 'Instagram Link', 'field' => 'social_instagram', 'value' => setting('social.instagram', ''), 'width' => 4])
    <hr class="splitter"/>
    @include('partials.fields.submit')
    <hr class="splitter"/>
    <a href="{{ route('orders.reminders') }}" class="btn btn-success">View Due Reminders</a>
    <hr class="splitter"/>
    <div class="col-sm-12">
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col" class="col-10">Mail</th>
                <th scope="col" class="col-2">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach(\App\Repository\Mailing\MailRepository::getAvailableMail() as $mail => $info)
                <tr>
                    <td>{{ ucwords(str_replace('-', ' ', $mail)) }}</td>
                    <td>
                        <a href="{{route('email.edit', ['mail' => $mail,])}}"
                           class="btn btn-outline-success btn-sm mb-1">
                            <i class="icon-note">&nbsp;Edit</i>
                        </a>
                        <a href="{{route('email.demo', ['mail' => $mail,])}}" class="btn btn-outline-info btn-sm mb-1">
                            <i class="icon-envelope-letter">&nbsp;Demo</i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
