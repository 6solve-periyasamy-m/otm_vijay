@section('head-script')
    <script type="text/javascript">
        $(document).ready(function() {
            let hatSizeSelect = $('#hat_size_id-input');
            hatSizeSelect.select2({
                ajax: {
                    url: '{{ route('api.hat-size.select') }}',
                    data: function (params) { return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',}; }
                }
            });
            $.ajax({ url: '{{ route('api.hat-size.selected', ['id' => $hat_size_id ?? 0, ]) }}', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', } })
                .then(function (data) {
                    hatSizeSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    hatSizeSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
            let tShirtSizeSelect = $('#t_shirt_size_id-input');
            tShirtSizeSelect.select2({
                ajax: {
                    url: '{{ route('api.t-shirt-size.select') }}',
                    data: function (params) { return {filter: params.term, __api_token: '{{ Auth::user()->getCurrentToken()->token }}',}; }
                }
            });
            $.ajax({ url: '{{ route('api.t-shirt-size.selected', ['id' => $t_shirt_size_id ?? 0, ]) }}', data: { __api_token: '{{ Auth::user()->getCurrentToken()->token }}', } })
                .then(function (data) {
                    tShirtSizeSelect.append(new Option(data.text, data.id, true, true)).trigger('change');

                    tShirtSizeSelect.trigger({
                        type: 'select2:select',
                        params: { data: data, }
                    });
                });
            changeBillingForm();
        });
        function changeBillingForm() {
            let disable = $('#home_is_billing-input').is(':checked');
            if (disable) { $('.billing-address').hide() } else { $('.billing-address').show() }
        }
    </script>
@endsection
<div class="card">
    <div class="card-body">
        <form action="{{ $action }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="form-group col-12">
                    <label for="title-input">Title</label>
                    <input name="title" value="{{ $title ?? "" }}" class="form-control" id="title-input">
                </div>                
                <div class="form-group col-12 col-xl-4">
                    <label for="first_name-input">First Name</label>
                    <input name="first_name" value="{{ $first_name ?? "" }}" class="form-control" id="first_name-input">
                </div>                
                <div class="form-group col-12 col-xl-4">
                    <label for="middle_names-input">Middle Names</label>
                    <input name="middle_names" value="{{ $middle_names ?? "" }}" class="form-control" id="middle_names-input">
                </div>                
                <div class="form-group col-12 col-xl-4">
                    <label for="last_name-input">Last Name</label>
                    <input name="last_name" value="{{ $last_name ?? "" }}" class="form-control" id="last_name-input">
                </div>                
                <div class="form-group col-12 col-xl-4">
                    <label for="gender-input">Gender</label>
                    <input name="gender" value="{{ $gender ?? "" }}" class="form-control" id="gender-input">
                </div>                
                <div class="form-group col-12 col-xl-4">
                    <label for="date_of_birth-input">Date Of Birth</label>
                    <input type="date" name="date_of_birth" value="{{ $date_of_birth ?? "" }}" class="form-control" id="date_of_birth-input">
                </div>
                <div class="form-group col-12 col-xl-4">
                    <label for="profile_picture-input">Profile Picture</label>
                    <input type="file" name="profile_picture" class="form-control" id="profile_picture-input">
                </div>
                <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
                <div class="form-group col-12">
                    <label for="email_address-input">Email Address</label>
                    <input name="email_address" value="{{ $email_address ?? "" }}" class="form-control" id="email_address-input">
                </div>                
                <div class="form-group col-12">
                    <label for="password-input">Password</label>
                    <input type="password" name="password" class="form-control" id="password-input">
                </div>                
                <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
                <div class="form-group col-12 col-xl-6">
                    <label for="home_address_line_1-input">Home Address Line 1</label>
                    <input name="home_address_line_1" value="{{ $home_address_line_1 ?? "" }}" class="form-control" id="home_address_line_1-input">
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="home_address_line_2-input">Home Address Line 2</label>
                    <input name="home_address_line_2" value="{{ $home_address_line_2 ?? "" }}" class="form-control" id="home_address_line_2-input">
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="home_town-input">Home Town</label>
                    <input name="home_town" value="{{ $home_town ?? "" }}" class="form-control" id="home_town-input">
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="region-input">Home Region</label>
                    <input name="region" value="{{ $home_region ?? "" }}" class="form-control" id="home_region-input">
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="home_country-input">Home Country</label>
                    <input name="home_country" value="{{ $home_country ?? "" }}" class="form-control" id="home_country-input">
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="home_postcode-input">Home Postcode</label>
                    <input name="home_postcode" value="{{ $home_postcode ?? "" }}" class="form-control" id="home_postcode-input">
                </div>                
                <div class="form-group col-12">
                    <input type="checkbox" name="home_is_billing" class="form-check-input"
                        @if(isset($home_address_id) && isset($billing_address_id) && $home_address_id == $billing_address_id) checked @endif
                    id="home_is_billing-input" onchange="changeBillingForm()">
                    <label for="home_is_billing-input" class="form-check-label">Billing Address is Same As Home</label>
                </div>
                <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;" class="billing-address"/>
                <div class="form-group col-12 col-xl-6 billing-address">
                    <label for="billing_address_line_1-input">Billing Address Line 1</label>
                    <input name="billing_address_line_1" value="{{ $billing_address_line_1 ?? "" }}" class="form-control" id="billing_address_line_1-input">
                </div>
                <div class="form-group col-12 col-xl-6 billing-address">
                    <label for="billing_address_line_2-input">Billing Address Line 2</label>
                    <input name="billing_address_line_2" value="{{ $billing_address_line_2 ?? "" }}" class="form-control" id="billing_address_line_2-input">
                </div>
                <div class="form-group col-12 col-xl-6 billing-address">
                    <label for="billing_town-input">Billing Town</label>
                    <input name="billing_town" value="{{ $billing_town ?? "" }}" class="form-control" id="billing_town-input">
                </div>
                <div class="form-group col-12 col-xl-6 billing-address">
                    <label for="region-input">Billing Region</label>
                    <input name="region" value="{{ $billing_region ?? "" }}" class="form-control" id="billing_region-input">
                </div>
                <div class="form-group col-12 col-xl-6 billing-address">
                    <label for="billing_country-input">Billing Country</label>
                    <input name="billing_country" value="{{ $billing_country ?? "" }}" class="form-control" id="billing_country-input">
                </div>
                <div class="form-group col-12 col-xl-6 billing-address">
                    <label for="billing_postcode-input">Billing Postcode</label>
                    <input name="billing_postcode" value="{{ $billing_postcode ?? "" }}" class="form-control" id="billing_postcode-input">
                </div>

                <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
                <div class="form-group col-12 col-xl-6">
                    <label for="mobile_number-input">Mobile Number</label>
                    <input name="mobile_number" value="{{ $mobile_number ?? "" }}" class="form-control" id="mobile_number-input">
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="other_phone_number-input">Other Phone Number</label>
                    <input name="other_phone_number" value="{{ $other_phone_number ?? "" }}" class="form-control"
                        id="other_phone_number-input">
                </div>                
                <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
                <div class="form-group col-12 col-xl-6">
                    <label for="emergency_contact_name-input">Emergency Contact Name</label>
                    <input name="emergency_contact_name" value="{{ $emergency_contact_name ?? "" }}" class="form-control"
                        id="emergency_contact_name-input">
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="emergency_contact_relationship-input">Emergency Contact Relationship</label>
                    <input name="emergency_contact_relationship" value="{{ $emergency_contact_relationship ?? "" }}"
                        class="form-control" id="emergency_contact_relationship-input">
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="emergency_contact_telephone-input">Emergency Contact Telephone</label>
                    <input name="emergency_contact_telephone" value="{{ $emergency_contact_telephone ?? "" }}" class="form-control"
                        id="emergency_contact_telephone-input">
                </div>                
                <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
                <div class="form-group col-12 col-xl-6">
                    <label for="passport_first_name-input">Passport First Name</label>
                    <input name="passport_first_name" value="{{ $passport_first_name ?? "" }}" class="form-control"
                        id="passport_first_name-input">
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="passport_middle_name-input">Passport Middle Name</label>
                    <input name="passport_middle_name" value="{{ $passport_middle_name ?? "" }}" class="form-control"
                        id="passport_middle_name-input">
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="passport_last_name-input">Passport Last Name</label>
                    <input name="passport_last_name" value="{{ $passport_last_name ?? "" }}" class="form-control"
                        id="passport_last_name-input">
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="passport_number-input">Passport Number</label>
                    <input name="passport_number" value="{{ $passport_number ?? "" }}" class="form-control"
                        id="passport_number-input">
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="passport_issue_date-input">Passport Issue Date</label>
                    <input type="date" name="passport_issue_date" value="{{ $passport_issue_date ?? "" }}" class="form-control"
                        id="passport_issue_date-input">
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="passport_expiry_date-input">Passport Expiry Date</label>
                    <input type="date" name="passport_expiry_date" value="{{ $passport_expiry_date ?? "" }}" class="form-control"
                        id="passport_expiry_date-input">
                </div>                
                <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
                <div class="form-group col-12 col-xl-6">
                    <label for="t_shirt_size_id-input">T Shirt Size</label>
                    <div class="d-flex">
                        <select name="t_shirt_size_id" class="form-control" id="t_shirt_size_id-input"></select>
                        <a href="{{ route('t-shirt-sizes.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>                
                <div class="form-group col-12 col-xl-6">
                    <label for="hat_size_id-input">Hat Size</label>
                    <div class="d-flex">
                        <select name="hat_size_id" class="form-control" id="hat_size_id-input"></select>
                        <a href="{{ route('hat-sizes.create') }}" target="_blank" class="btn btn-success d-inline ms-1">+</a>
                    </div>
                </div>                
                <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
                <div class="form-group col-12">
                    <label for="loyalty_number-input">Loyalty Number</label>
                    <input name="loyalty_number" value="{{ $loyalty_number ?? "" }}" class="form-control" id="loyalty_number-input">
                </div>                
                <div class="form-group col-12">
                    <label for="notes-input">Notes</label>
                    <textarea name="notes" class="form-control" id="notes-id">{{ $notes ?? "" }}</textarea>                    
                </div>                
                <div class="form-group col-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
