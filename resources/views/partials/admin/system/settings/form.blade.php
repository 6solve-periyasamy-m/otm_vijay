<form enctype="multipart/form-data" id="settings-form" autocomplete="off" action="{{ route('settings.update') }}" method="post" class="row">
    @csrf
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4 class="fw-bold">Company Information</h4>
                </div>
                <div class="row">
                    @include('partials.fields.text', ['name' => 'Company Name', 'field' => 'company_name', 'value' => setting('company.name', ''), 'width' => 6])
                    @include('partials.fields.text', ['name' => 'Company Email', 'field' => 'company_email', 'value' => setting('company.contact.email', ''), 'width' => 6])
                    @include('partials.fields.text', ['name' => 'Company Phone Number', 'field' => 'company_phone', 'value' => setting('company.contact.phone', ''), 'width' => 6])
                    @include('partials.fields.text', ['name' => 'Company VAT', 'field' => 'company_vat', 'value' => setting('company.vat', ''), 'width' => 6])
                    @include('partials.fields.text', ['name' => 'Company Link', 'field' => 'company_url', 'value' => setting('company.url', ''), 'width' => 3])
                    @include('partials.fields.text', ['name' => 'Facebook Link', 'field' => 'social_facebook', 'value' => setting('social.facebook', ''), 'width' => 3])
                    @include('partials.fields.text', ['name' => 'Twitter Link', 'field' => 'social_twitter', 'value' => setting('social.twitter', ''), 'width' => 3])
                    @include('partials.fields.text', ['name' => 'Instagram Link', 'field' => 'social_instagram', 'value' => setting('social.instagram', ''), 'width' => 3])
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4 class="fw-bold">Company Address</h4>
                </div>
                <div class="row">
                    @include('partials.fields.text', ['name' => 'Company Address Line 1', 'field' => 'address_line_1', 'value' => setting('company.address.line_1', ''), 'width' => 6])
                    @include('partials.fields.text', ['name' => 'Company Address Line 2', 'field' => 'address_line_2', 'value' => setting('company.address.line_2', ''), 'width' => 6])
                    @include('partials.fields.text', ['name' => 'Company Address City', 'field' => 'city', 'value' => setting('company.address.city', ''), 'width' => 6])
                    @include('partials.fields.text', ['name' => 'Company Address Region', 'field' => 'region', 'value' => setting('company.address.region', ''), 'width' => 6])
                    @include('partials.fields.text', ['name' => 'Company Address Country', 'field' => 'country', 'value' => setting('company.address.country', ''), 'width' => 6])
                    @include('partials.fields.text', ['name' => 'Company Address Postcode', 'field' => 'postcode', 'value' => setting('company.address.postcode', ''), 'width' => 6])
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4 class="fw-bold">Order and Booking Information</h4>
                </div>
                <div class="row">
                    @include('partials.fields.text', ['name' => 'Order Prefix ', 'field' => 'booking_prefix', 'value' => setting('booking.prefix', 'OTM'), 'width' => 4])
                    @include('partials.fields.text', ['name' => 'Quote Prefix', 'field' => 'quote_prefix', 'value' => setting('quote.prefix', 'OTMQ'), 'width' => 4])
                    @include('partials.fields.selector.default', ['name' => 'System Currency', 'field' => 'currency_id', 'value' => \App\Repository\LocationsRepository::getCurrencyIdByCode(setting('system.currency', '')) ?? null, 'route' => 'currencies', 'width' => 4,])
                    @include('partials.fields.text', ['name' => 'ATOL Issuer', 'field' => 'atol_issuer', 'value' => setting('atol.issuer', ''), 'width' => 6])
                    @include('partials.fields.text', ['name' => 'ATOL Number', 'field' => 'atol_number', 'value' => setting('atol.number', ''), 'width' => 6])
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4 class="fw-bold">Payment Success Redirects</h4>
                </div>
                <div class="row">
                    @include('partials.fields.text', ['name' => 'Booking Success Page', 'field' => 'booking_redirect', 'value' => setting('booking.success.redirect', ''), 'width' => 6,])
                    @include('partials.fields.text', ['name' => 'Installment Payment Success Page', 'field' => 'payment_redirect', 'value' => setting('payment.success.redirect', ''), 'width' => 6,])
                    @include('partials.fields.text', ['name' => 'Add-on Purchase Success Page', 'field' => 'addon_redirect', 'value' => setting('purchase.addon.success.redirect', ''), 'width' => 6,])
                    @include('partials.fields.text', ['name' => 'Upgrade Purchase Success Page', 'field' => 'upgrade_redirect', 'value' => setting('purchase.upgrade.success.redirect', ''), 'width' => 6,])
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4 class="fw-bold">Dates and Times</h4>
                </div>
                <div class="row">
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
                    @include('partials.fields.selector.default', ['name' => 'ATOL Filter Country', 'field' => 'atol_filter', 'value' => \Settings::atolFilter(), 'route' => 'countries.filter', 'width' => 4])
                    @include('partials.fields.date', ['name' => 'Financial Year Start', 'field' => 'year_start', 'value' => setting('system.year.start', '2022-04-01'), 'width' => 4])
                    @include('partials.fields.date', ['name' => 'ATOL Year Start', 'field' => 'atol_start', 'value' => setting('atol.year.start', '2022-04-01'), 'width' => 4])
                    @include('partials.fields.text', ['name' => 'Historic After X Months', 'field' => 'historic', 'value' => setting('system.historic', 6), 'width' => 4])
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4 class="fw-bold">System Toggles</h4>
                </div>
                <div class="row">
                    @include('partials.fields.checkbox', ['name' => 'Require Immediate Payment For Upgrades/Add-ons', 'field' => 'payment_required', 'value' => flag('payment.required', true),])
                    @include('partials.fields.checkbox', ['name' => 'Require Overdue Installments Up Front', 'field' => 'force_installments', 'value' => flag('installments.force', false),])
                    @include('partials.fields.checkbox', ['name' => 'Default to Sending Booking Confirmation on Manual Order Creation', 'field' => 'mail_enabled', 'value' => flag('order.manual.mail', false),])
                    @include('partials.fields.checkbox', ['name' => 'Enable Automatically Sending Emails', 'field' => 'mail_enabled', 'value' => flag('system.mail.enabled', true),])
                    @include('partials.fields.checkbox', ['name' => 'Enable ATOL Certificate Generation', 'field' => 'atol_enabled', 'value' => flag('atol.enabled', true),])
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4 class="fw-bold">System Images</h4>
                </div>
                <div class="row">
                    @include('partials.fields.file', ['name' => 'Company Logo', 'field' => 'company_logo', 'width' => 6])
                    <div class="col-12 col-xl-6">
                        <img class="image medium" src="{{ asset(setting('company.logo')) }}"/>
                    </div>
                    @include('partials.fields.file', ['name' => 'ATOL Stamp', 'field' => 'atol_stamp', 'width' => 6])
                    <div class="col-12 col-xl-6">
                        <img class="image tiny" src="{{ asset(setting('atol.stamp')) }}"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title">
                    <h4 class="fw-bold">Customer Data Locking</h4>
                </div>
                <div class="row">
                    @include('partials.admin.system.settings.lock', ['name' => 'Purchasing Components', 'field' => 'components', 'unlock' => false])
                    @include('partials.admin.system.settings.lock', ['name' => 'Passport Details', 'field' => 'passport'])
                    @include('partials.admin.system.settings.lock', ['name' => 'Order Notes', 'field' => 'order_notes'])
                    @include('partials.admin.system.settings.lock', ['name' => 'Accommodation Notes', 'field' => 'accommodation'])
                    @include('partials.admin.system.settings.lock', ['name' => 'Activity Notes', 'field' => 'activity'])
                    @include('partials.admin.system.settings.lock', ['name' => 'Flight Notes', 'field' => 'flight'])
                    @include('partials.admin.system.settings.lock', ['name' => 'Transport Notes', 'field' => 'transport'])
                </div>
            </div>
        </div>
    </div>
</form>
