@push('header-stack')
    <script type="text/javascript">
        function changeBillingForm() {
            let disable = $('#home_is_billing-input').is(':checked');
            if (disable) {
                $('.billing-address').hide()
            } else {
                $('.billing-address').show()
            }
        }
    </script>
@endpush
@push('header-ready')
    changeBillingForm();
@endpush
@include('partials.fields.text', ['name' => 'Title', 'field' => 'title', 'value' => $title ?? null, 'width' => 1,])
@include('partials.fields.text', ['name' => 'First Name', 'field' => 'first_name', 'value' => $first_name ?? null, 'width' => 3,])
@include('partials.fields.text', ['name' => 'Middle Names', 'field' => 'middle_names', 'value' => $middle_names ?? null, 'width' => 4,])
@include('partials.fields.text', ['name' => 'Last Name', 'field' => 'last_name', 'value' => $last_name ?? null, 'width' => 4,])
@include('partials.fields.text', ['name' => 'Gender', 'field' => 'gender', 'value' => $gender ?? null, 'width' => 4,])
@include('partials.fields.date', ['name' => 'Date Of Birth', 'field' => 'date_of_birth', 'value' => $date_of_birth ?? null, 'width' => 4,])
@include('partials.fields.file', ['name' => 'Profile Picture', 'field' => 'profile_picture', 'width' => 4,])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
@include('partials.fields.text', ['name' => 'Email Address', 'field' => 'email_address', 'value' => $email_address ?? null, 'width' => 6,])
@include('partials.fields.password', ['name' => 'Password', 'field' => 'password', 'width' => 6,])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
@include('partials.fields.text', ['name' => 'Home Address Line 1', 'field' => 'home_address_line_1', 'value' => $home_address_line_1 ?? null, 'width' => 6,])
@include('partials.fields.text', ['name' => 'Home Address Line 2', 'field' => 'home_address_line_2', 'value' => $home_address_line_2 ?? null, 'width' => 6,])
@include('partials.fields.text', ['name' => 'Home Town', 'field' => 'home_town', 'value' => $home_town ?? null, 'width' => 6,])
@include('partials.fields.text', ['name' => 'Home Region', 'field' => 'home_region', 'value' => $home_region ?? null, 'width' => 6,])
@include('partials.fields.selector.default', ['name' => 'Home Country', 'field' => 'home_country', 'value' => $home_country ?? null, 'width' => 6, 'route' => 'countries',])
@include('partials.fields.text', ['name' => 'Home Postcode', 'field' => 'home_postcode', 'value' => $home_postcode ?? null, 'width' => 6,])
@include('partials.fields.checkbox', ['name' => 'Billing Address is Same As Home', 'field' => 'home_is_billing',
'value' => (isset($home_address_id) && isset($billing_address_id) && $home_address_id == $billing_address_id) ? 1 : 0, 'onChange' => 'changeBillingForm();'])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;" class="billing-address"/>
@include('partials.fields.text', ['name' => 'Billing Address Line 1', 'field' => 'billing_address_line_1', 'value' => $billing_address_line_1 ?? null, 'width' => 6, 'divClasses' => 'billing-address',])
@include('partials.fields.text', ['name' => 'Billing Address Line 2', 'field' => 'billing_address_line_2', 'value' => $billing_address_line_2 ?? null, 'width' => 6, 'divClasses' => 'billing-address',])
@include('partials.fields.text', ['name' => 'Billing Town', 'field' => 'billing_town', 'value' => $billing_town ?? null, 'width' => 6, 'divClasses' => 'billing-address',])
@include('partials.fields.text', ['name' => 'Billing Region', 'field' => 'billing_region', 'value' => $billing_region ?? null, 'width' => 6, 'divClasses' => 'billing-address',])
@include('partials.fields.selector.default', ['name' => 'Billing Country', 'field' => 'billing_country', 'value' => $billing_country ?? null, 'width' => 6, 'divClasses' => 'billing-address', 'route' => 'countries',])
@include('partials.fields.text', ['name' => 'Billing Postcode', 'field' => 'billing_postcode', 'value' => $billing_postcode ?? null, 'width' => 6, 'divClasses' => 'billing-address',])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
@include('partials.fields.text', ['name' => 'Mobile Number', 'field' => 'mobile_number', 'value' => $mobile_number ?? null, 'width' => 6,])
@include('partials.fields.text', ['name' => 'Other Phone Number', 'field' => 'other_phone_number', 'value' => $other_phone_number ?? null, 'width' => 6,])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
@include('partials.fields.text', ['name' => 'Emergency Contact Name', 'field' => 'emergency_contact_name', 'value' => $emergency_contact_name ?? null, 'width' => 4,])
@include('partials.fields.text', ['name' => 'Emergency Contact Relationship', 'field' => 'emergency_contact_relationship', 'value' => $emergency_contact_relationship ?? null, 'width' => 4,])
@include('partials.fields.text', ['name' => 'Emergency Contact Telephone', 'field' => 'emergency_contact_telephone', 'value' => $emergency_contact_telephone ?? null, 'width' => 4,])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
@include('partials.fields.text', ['name' => 'Passport First Name', 'field' => 'passport_first_name', 'value' => $passport_first_name ?? null, 'width' => 4,])
@include('partials.fields.text', ['name' => 'Passport Middle Name', 'field' => 'passport_middle_name', 'value' => $passport_middle_name ?? null, 'width' => 4,])
@include('partials.fields.text', ['name' => 'Passport Last Name', 'field' => 'passport_last_name', 'value' => $passport_last_name ?? null, 'width' => 4,])
@include('partials.fields.text', ['name' => 'Passport Number', 'field' => 'passport_number', 'value' => $passport_number ?? null, 'width' => 4,])
@include('partials.fields.date', ['name' => 'Passport Issue Date', 'field' => 'passport_issue_date', 'value' => $passport_issue_date ?? null, 'width' => 4,])
@include('partials.fields.date', ['name' => 'Passport Expiry Date', 'field' => 'passport_expiry_date', 'value' => $passport_expiry_date ?? null, 'width' => 4,])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
@include('partials.fields.selector.adder', ['name' => 'T-Shirt Size', 'field' => 't_shirt_size_id', 'value' => $t_shirt_size_id ?? 0,
'route' => 't-shirt-size', 'createRoute' => route('t-shirt-sizes.create'), 'width' => 4])
@include('partials.fields.selector.adder', ['name' => 'Hat Size', 'field' => 'hat_size_id', 'value' => $hat_size_id ?? 0,
'route' => 'hat-size', 'createRoute' => route('hat-sizes.create'), 'width' => 4])
@include('partials.fields.text', ['name' => 'Loyalty Number', 'field' => 'loyalty_number', 'value' => $loyalty_number ?? null, 'width' => 4])
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>

@include('partials.fields.prefab.notes')
@include('partials.fields.submit')
