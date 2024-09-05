<form enctype="multipart/form-data" id="settings-form" autocomplete="off" action="{{ route('settings.update') }}" method="post" class="row">
    @csrf
    <div class="col-xl-6">
        <x-admin.section.card>
            <x-slot:title>
                Company Information
            </x-slot:title>
            <div class="row">
                @include('partials.fields.text', ['name' => 'Company Name', 'field' => 'company_name', 'value' => setting('company.name', ''), 'width' => 6])
                @include('partials.fields.text', ['name' => 'Company Email', 'field' => 'company_email', 'value' => setting('company.contact.email', ''), 'width' => 6])
                @include('partials.fields.text', ['name' => 'Company Phone Number', 'field' => 'company_phone', 'value' => setting('company.contact.phone', ''), 'width' => 6])
                @include('partials.fields.text', ['name' => 'Company VAT / ABN', 'field' => 'company_vat', 'value' => setting('company.vat', ''), 'width' => 6])
                @include('partials.fields.text', ['name' => 'Company Link', 'field' => 'company_url', 'value' => setting('company.url', ''), 'width' => 3])
                @include('partials.fields.text', ['name' => 'Facebook Link', 'field' => 'social_facebook', 'value' => setting('social.facebook', ''), 'width' => 3])
                @include('partials.fields.text', ['name' => 'Twitter Link', 'field' => 'social_twitter', 'value' => setting('social.twitter', ''), 'width' => 3])
                @include('partials.fields.text', ['name' => 'Instagram Link', 'field' => 'social_instagram', 'value' => setting('social.instagram', ''), 'width' => 3])
            </div>
        </x-admin.section.card>
    </div>
    <div class="col-xl-6">
        <x-admin.section.card>
            <x-slot:title>
                Company Address
            </x-slot:title>
            <div class="row">
                @include('partials.fields.text', ['name' => 'Company Address Line 1', 'field' => 'address_line_1', 'value' => setting('company.address.line_1', ''), 'width' => 6])
                @include('partials.fields.text', ['name' => 'Company Address Line 2', 'field' => 'address_line_2', 'value' => setting('company.address.line_2', ''), 'width' => 6])
                @include('partials.fields.text', ['name' => 'Company Address City', 'field' => 'city', 'value' => setting('company.address.city', ''), 'width' => 6])
                @include('partials.fields.text', ['name' => 'Company Address Region', 'field' => 'region', 'value' => setting('company.address.region', ''), 'width' => 6])
                @include('partials.fields.text', ['name' => 'Company Address Country', 'field' => 'country', 'value' => setting('company.address.country', ''), 'width' => 6])
                @include('partials.fields.text', ['name' => 'Company Address Postcode', 'field' => 'postcode', 'value' => setting('company.address.postcode', ''), 'width' => 6])
            </div>
    </x-admin.section.card>
    </div>
    <div class="col-xl-6">
        <x-admin.section.card>
            <div class="card-body">
                <x-slot:title>
                    Order and Booking Information
                </x-slot:title>
                <div class="row">
                    @include('partials.fields.text', ['name' => 'Order Prefix ', 'field' => 'booking_prefix', 'value' => setting('booking.prefix', 'OTM'), 'width' => 4])
                    @include('partials.fields.text', ['name' => 'Quote Prefix', 'field' => 'quote_prefix', 'value' => setting('quote.prefix', 'OTMQ'), 'width' => 4])
                    <x-livewire.input.select.currency name="currency_id" label="System Currency" value="{{\App\Repository\LocationsRepository::getCurrencyIdByCode(setting('system.currency', '')) ?? null}}" width="4" />
                    @include('partials.fields.text', ['name' => 'ATOL Issuer', 'field' => 'atol_issuer', 'value' => setting('atol.issuer', ''), 'width' => 6])
                    @include('partials.fields.text', ['name' => 'ATOL Number', 'field' => 'atol_number', 'value' => setting('atol.number', ''), 'width' => 6])
                    @include('partials.fields.dropdown', [
                        'name' => 'Invoice Format',
                        'field' => 'invoice_format',
                        'values' => \Settings::availableInvoiceStyles(),
                        'selected' => (int)setting('invoice.style', 1),
                        'width' => 4,
                    ])
                    @include('partials.fields.dropdown', [
                        'name' => 'Quote Format',
                        'field' => 'quote_format',
                        'values' => \Settings::availableQuoteStyles(),
                        'selected' => (int)setting('quote.style', 1),
                        'width' => 4,
                    ])
                    @include('partials.fields.dropdown', [
                        'name' => 'Itinerary Format',
                        'field' => 'itinerary_format',
                        'values' => \Settings::availableItineraryStyles(),
                        'selected' => (int)setting('itinerary.style', 1),
                        'width' => 4,
                    ])
                </div>
            </div>
        </x-admin.section.card>
    </div>
    <div class="col-xl-6">
        <x-admin.section.card>
            <x-slot:title>
                Payment Success Redirects
            </x-slot:title>
            <div class="row">
                @include('partials.fields.text', ['name' => 'Booking Success Page', 'field' => 'booking_redirect', 'value' => setting('booking.success.redirect', ''), 'width' => 6,])
                @include('partials.fields.text', ['name' => 'Installment Payment Success Page', 'field' => 'payment_redirect', 'value' => setting('payment.success.redirect', ''), 'width' => 6,])
                @include('partials.fields.text', ['name' => 'Add-on Purchase Success Page', 'field' => 'addon_redirect', 'value' => setting('purchase.addon.success.redirect', ''), 'width' => 6,])
                @include('partials.fields.text', ['name' => 'Upgrade Purchase Success Page', 'field' => 'upgrade_redirect', 'value' => setting('purchase.upgrade.success.redirect', ''), 'width' => 6,])
            </div>
    </x-admin.section.card>
    </div>
    <div class="col-xl-4">
        <x-admin.section.card>
            <x-slot:title>
                Dates and Times
            </x-slot:title>
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
    </x-admin.section.card>
    </div>
    <div class="col-xl-4">
        <x-admin.section.card>
            <x-slot:title>
                System Toggles
            </x-slot:title>
            <div class="row">
                @include('partials.fields.checkbox', ['name' => 'Require Immediate Payment For Upgrades/Add-ons', 'field' => 'payment_required', 'value' => flag('payment.required', true),])
                @include('partials.fields.checkbox', ['name' => 'Require Overdue Installments Up Front', 'field' => 'force_installments', 'value' => flag('installments.force', false),])
                @include('partials.fields.checkbox', ['name' => 'Default to Sending Booking Confirmation on Manual Order Creation', 'field' => 'mail_enabled', 'value' => flag('order.manual.mail', false),])
                @include('partials.fields.checkbox', ['name' => 'Enable Automatically Sending Emails', 'field' => 'mail_enabled', 'value' => flag('system.mail.enabled', true),])
                @include('partials.fields.checkbox', ['name' => 'Enable ATOL Certificate Generation', 'field' => 'atol_enabled', 'value' => flag('atol.enabled', true),])
                @include('partials.fields.checkbox', ['name' => 'BCC Emails to Sender', 'field' => 'bcc_sender', 'value' => flag('mail.bcc-sender', false),])
                @include('partials.fields.checkbox', ['name' => 'BCC Emails to Consultant', 'field' => 'bcc_consultant', 'value' => flag('mail.bcc-consultant', false),])
                @include('partials.fields.checkbox', ['name' => 'Should Booking Deposit Percentage Include Additional Costs', 'field' => 'deposit_full', 'value' => flag('booking.deposit.full', false),])
            </div>
    </x-admin.section.card>
    </div>
    <div class="col-xl-4">
        <x-admin.section.card>
            <x-slot:title>
                System Images
            </x-slot:title>
            <div class="row">
                @include('partials.fields.file', ['name' => 'Company Logo', 'field' => 'company_logo', 'width' => 6])
                <div class="col-12 col-xl-6">
                    <img class="image medium" src="{{ asset(setting('company.logo')) }}"/>
                </div>
                @include('partials.fields.file', ['name' => 'Alternative Company Logo', 'field' => 'alt_company_logo', 'width' => 6])
                <div class="col-12 col-xl-6">
                    <img class="image medium" src="{{ asset(setting('company.logo.alternative', setting('company.logo'))) }}"/>
                </div>
                @include('partials.fields.file', ['name' => 'ATOL Stamp', 'field' => 'atol_stamp', 'width' => 6])
                <div class="col-12 col-xl-6">
                    <img class="image tiny" src="{{ asset(setting('atol.stamp')) }}"/>
                </div>
            </div>
    </x-admin.section.card>
    </div>
    <div class="col-xl-12">
        <x-admin.section.card>
            <x-slot:title>
                Customer Data Locking
            </x-slot:title>
            <div class="row">
                @include('partials.admin.system.settings.lock', ['name' => 'Purchasing Components', 'field' => 'components', 'unlock' => false])
                @include('partials.admin.system.settings.lock', ['name' => 'Passport Details', 'field' => 'passport'])
                @include('partials.admin.system.settings.lock', ['name' => 'Order Notes', 'field' => 'order_notes'])
                @include('partials.admin.system.settings.lock', ['name' => 'Accommodation Notes', 'field' => 'accommodation'])
                @include('partials.admin.system.settings.lock', ['name' => 'Activity Notes', 'field' => 'activity'])
                @include('partials.admin.system.settings.lock', ['name' => 'Flight Notes', 'field' => 'flight'])
                @include('partials.admin.system.settings.lock', ['name' => 'Transport Notes', 'field' => 'transport'])
            </div>
        </x-admin.section.card>
    </div>
    <div class="col-xl-6">
        <x-admin.section.card>
            <x-slot:title>Bank Transfer Details</x-slot:title>
            @include('partials.fields.ckeditor', ['name' => 'Bank Transfer', 'field' => 'bank_transfer', 'value' => setting('company.bank_transfer', ''), 'width' => 12])
        </x-admin.section.card>
    </div>
    @if(config('app.features.kpt') || config('app.features.bleeding-edge'))
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:title>Default Documentation Colors</x-slot:title>
                <x-livewire.input.code-mirror name="document_css">{{ setting('customization.documentation.colors', \Settings::defaultDocumentationColors()) }}</x-livewire.input.code-mirror>
            </x-admin.section.card>
        </div>
    @endif
</form>
