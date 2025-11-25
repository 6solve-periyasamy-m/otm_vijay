@extends('layout.customer')

@php
    $currentUser = \App\Repository\Authentication\CustomerAuthenticationRepository::getCustomer();
    $self = $currentUser->id === $customer->id;
    $passport = $customer->repository->isPassportLocked();
    use App\Models\Location\Country;
    use App\Models\Customer\TShirtSize;
    use App\Models\Customer\HatSize;
@endphp

@section('title', 'Edit Customer Profile')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"></script>
<style>
    .iti__selected-flag { z-index: 1; position: relative; display: flex; align-items: center; height: 100%; padding: 4px 0px 14px 7px !important }
    .two_input_field.mobile_number .iti{width: 100%;}
    .two_input_field.mobile_number .iti--separate-dial-code .iti__selected-flag{background: transparent;padding-left: 24px !important;padding-bottom: 7px !important;}
    .two_input_field.mobile_number .iti__arrow { width: 1px; height: 17px; display: inline-block; background: #F35B15; border: unset !important; margin-left: 7px; }
    .two_input_field.mobile_number .iti__selected-dial-code { font-family: "PP Neue Montreal" !important; font-size: 14px !important; line-height: 24px !important; color: #721111 !important; }
    .two_input_field.mobile_number ul li::after{display: none;} .two_input_field.mobile_number ul li{padding: 5px 10px;border-bottom: 0px !important;}
    .two_input_field.mobile_number ul li.iti__divider{padding:0px;border-bottom: 0px;}
    .two_input_field.mobile_number .iti__country-name { font-family: "PP Neue Montreal"; font-size: 14px; line-height: 26px; padding-right: 10px; margin-left: 10px; color: #000; text-transform: capitalize; }
    .two_input_field.mobile_number .iti__country-list{max-width: 340px;overflow-x: hidden;background-color:#F9F4EE;}
    .personal_details input{padding-right: 20px;} #mobile_number:focus{box-shadow: unset;}
    .two_input_field.mobile_number .iti__country-list::-webkit-scrollbar{width:5px;}
    .two_input_field.mobile_number .iti__country-list::-webkit-scrollbar-thumb { background: #F35B15; border-radius: 10px; }
    .iti--allow-dropdown input[type=tel], .iti--separate-dial-code input,.iti--allow-dropdown input{padding-left: 65px;}
</style>
@section('content')
    <div class="inner_content">
        <x-customer.overview-top-bar title="Your Details" :search="false" />
        <div class="your_details">
            <div class="your_details_row">
                <div class="your_details_colm_1">
                    <ul>
                        <li class="active"><a href="#personal_details">Personal Details</a></li>
                        <li><a href="#address_details">Address</a></li>
						{{--<li><a href="#passport_details">Passport Details</a></li>--}}
                        <li><a href="#frequent_details">Frequent Flyer Details</a></li>
                        <li><a href="#other_details">Other Details</a></li>
                        {{--<li><a href="#change_password">Change Password</a></li>--}}
                    </ul>
                </div>
                <div class="your_details_colm_2">
                    <form id="userform" @if (!isset($other))
                                  action="{{ route('customer.update') }}"
                              @else
                                  action="{{ route('customer.update.other', ['customer' => $customer,]) }}"
                              @endif
                              method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="profile_picture" id="profile_picture" style="display: none;" onchange="form.submit()">
                    <div class="form_inner">
                        <div class="personal_details" id="personal_details">
                            <input type="hidden" value="{{ $customer->id }}" name="id" id="id" >
                            <h3>Personal details</h3>
                            <p>Manage your personal details</p>
                            <div class="personal_details_form">
                                <h5>User Details</h5>
                                <div class="one_input_field"><label>Title</label><input type="text" name="title" value="{{ $customer->title ?? '' }}" autocomplete="honorific-prefix" required ></div>
                                <div class="two_input_field">
                                    <div class="two_label_field"><label>First Name</label><input type="text" name="first_name" value="{{ $customer->first_name ?? '' }}"  autocomplete="given-name" placeholder="First Name *" required></div>
									<div class="two_label_field"><label>Middle Name</label><input type="text" name="middle_names" value="{{ $customer->middle_names ?? '' }}"  autocomplete="middle-name" placeholder="Middle Name"></div>
                                    <div class="two_label_field"><label>Last Name</label><input type="text" name="last_name" value="{{ $customer->last_name ?? '' }}"  autocomplete="family-name"  placeholder="Last Name *" required></div>
                                </div>
                                <div class="two_input_field">
                                    <div class="two_label_field"><label>Date of Birth</label><input placeholder="DATE OF BIRTH*" type="date" name="date_of_birth" value="{{ $customer->date_of_birth?->format('Y-m-d') ?? '' }}" width="4" autocomplete="bday" required ></div>
                                    <div class="two_label_field"><label>Email Address</label><input type="email" placeholder="EMAIL ADDRESS" name="email_address" value="{{ $customer->email_address ?? '' }}"></div>
                                </div>
                                <div class="two_input_field mobile_number">
                                    <div class="two_label_field"><label>Mobile Number</label><input style="width: 100%;" type="tel" id="mobile_number" value="{{ old('mobile_number', $customer->mobile_number ?? '') }}" class=" phone-input form-control" required>
                                    <input type="hidden" name="mobile_number" id="mobile_number"  value="{{ old('mobile_number', $customer->mobile_number ?? '') }}">
                                    <span  style="color:red; display:none;">Please enter a valid phone number.</span></div>
                                    <div class="two_label_field"><label>Alternate Mobile Number</label><input type="tel" id="other_phone_number" value="{{ $customer->other_phone_number ?? '' }}" placeholder="ALTERNATE MOBILE NUMBER"  class="phone-input alternate_no mobile_no [&::-webkit-inner-spin-button]:appearance-none"  style="width: 100%;">
                                    <input type="hidden" name="other_phone_number" value="{{ $customer->other_phone_number ?? '' }}">
                                    <span  style="color:red; display:none;">Please enter a valid phone number.</span></div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="personal_details address_details" id="address_details">
                            <h3>Address</h3>
                            <p>Manage your address</p>
                            <div class="address_details_form">
                                <div class="address_detail_colm_1" id="home-address-section">
                                    <h5>Home Address</h5>
                                    <div class="one_input_field"><label>Address Line 1</label><input type="text" name="home_address_line_1" value="{{ $customer->homeAddress->address_line_1 ?? '' }}" autocomplete="address-line1"placeholder="ADDRESS LINE 1" required ></div>
                                    <div class="one_input_field"><label>Address Line 2</label><input type="text" name="home_address_line_2" value="{{ $customer->homeAddress->address_line_2 ?? '' }}" autocomplete="address-line2" placeholder="ADDRESS LINE 2" required ></div>
                                    <div class="one_input_field"><label>Post Code</label><input type="text" name="home_postcode" value="{{ $customer->homeAddress->postcode ?? '' }}" autocomplete="postcode" placeholder="POST CODE" required ></div>
                                    <div class="one_input_field"><label>Town</label><input type="text" name="home_town" value="{{ $customer->homeAddress->town ?? '' }}" autocomplete="address-level2"placeholder="TOWN" required ></div>
                                    <div class="one_input_field"><label>Region</label><input type="text" name="home_region" value="{{ $customer->homeAddress->region ?? '' }}" autocomplete="address-level1" placeholder="REGION" required ></div>
                                    <div class="one_input_field">
									<label>Select Country</label>
                                          <select name="home_country_id" id="home_country_id" class="form-control">
                                            <option value="">Select </option>
                                            @foreach(Country::all() as $country)
                                                <option value="{{ $country->id }}" {{ ($customer->homeAddress->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                                    {{ strtoupper($country->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="address_detail_colm_2">
                                    <div class="home_addr"><h5>Billing Address</h5>
                                    <span class="same_as">
                                        <label for="home_is_billing" class="hs-form-checkbox-display">
                                            @php
                                                $home_address_id    = $customer->home_address_id;
                                                $billing_address_id = $customer->billing_address_id;
                                            @endphp
                                          <input id="home_is_billing" class="hs-input" type="checkbox" name="home_is_billing"
                                            value="1"
                                            {{ (isset($home_address_id) && isset($billing_address_id) && $home_address_id == $billing_address_id) ? 'checked' : '' }}>
                                            <span>Same as Home</span>

                                            </label>
                                    </span>
                                    </div>
                                    <div class="one_input_field"><label>Address Line 1</label><input type="text"  name="billing_address_line_1" value="{{ $customer->billingAddress->address_line_1 ?? '' }}" autocomplete="address-line1" placeholder="ADDRESS LINE 1" ></div>
                                    <div class="one_input_field"><label>Address Line 2</label><input type="text" name="billing_address_line_2" value="{{ $customer->billingAddress->address_line_2 ?? '' }}" autocomplete="address-line2" placeholder="ADDRESS LINE 2"  ></div>
                                    <div class="one_input_field"><label>Post Code</label><input type="text" name="billing_postcode" value="{{ $customer->billingAddress->postcode ?? '' }}" autocomplete="postcode" placeholder="POST CODE" required ></div>
                                    <div class="one_input_field"><label>Town</label><input type="text" name="billing_town" value="{{ $customer->billingAddress->town ?? '' }}" autocomplete="address-level2" placeholder="TOWN" ></div>
                                    <div class="one_input_field"><label>Region</label><input type="text" name="billing_region" value="{{ $customer->billingAddress->region ?? '' }}" autocomplete="address-level1" placeholder="REGION" ></div>
                                    <div class="one_input_field">
									<label>Select Country</label>
                                       <select name="billing_country_id" id="billing_country_id" class="form-control">
                                            <option value="">Select </option>
                                            @foreach(Country::all() as $country)
                                                <option value="{{ $country->id }}" {{ ($customer->billingAddress->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                                    {{ strtoupper($country->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="personal_details contact_details" id="contact_details">
                            <h3>Emergency Contact Details</h3>
                            <div class="emergency_details_form">
                                <div class="two_input_field full_width_field">
								<label>Contact Name</label>
                                    <input type="text" placeholder="Contact Name" name="emergency_contact_name" value="{{ $customer->emergency_contact_name ?? '' }}">
                                </div>
                                <div class="two_input_field mobile_number">
									<div class="one_input_field"><label>Relationship</label>
										<input type="text" name="emergency_contact_relationship" value="{{ $customer->emergency_contact_relationship ?? '' }}" placeholder="RELATIONSHIP" class="relationship_input" required>
									</div>
									<div class="one_input_field"><label>Mobile Number</label>
                                    <input style="width: 100%;" type="tel" id="emergency_contact_telephone" value="{{ $customer->emergency_contact_telephone ?? '' }}" placeholder="MOBILE NUMBER" required   class=" phone-input mobile_no [&::-webkit-inner-spin-button]:appearance-none">
                                    <input type="hidden" name="emergency_contact_telephone" value="{{ $customer->emergency_contact_telephone ?? '' }}" >
									</div>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="personal_details other_details" id="other_details">
                            <h3>Other Details</h3>
                            <div class="other_details_form">
                                <div class="one_input_field"><div class="two_input_field full_width_field">
                                    <label>Other Notes</label><input type="text" name="other_notes" value="{{ $customer->external_notes }}"  placeholder="Other Notes"  ></div></div>
                                <div class="one_input_field"><div class="two_input_field full_width_field">
                                    <label>Dietary Requirements</label><input type="text"  name="dietary_notes" value="{{ $customer->dietary_notes }}" placeholder="Dietary Requirements"  ></div></div>
                                <div class="one_input_field"><div class="two_input_field full_width_field">
                                    <label>Mobility Requirements</label><input type="text" name="mobility_notes" value="{{ $customer->mobility_notes }}" placeholder="Mobility Requirements" ></div></div>
                            </div>
                        </div>
                        </hr>
                        {{--<div class="personal_details passport_details" id="passport_details">
                            <h3>Passport Details</h3>

                            @php
                                $passportdisabled = '';
                                $passportreadonly = '';
                            @endphp
                            @if ($passport)
                                <span class="fw-bold">Passport details are currently locked due to an upcoming tour. If you need to update your passport details, please contact us.</span>
                                @php
                                    $passportdisabled = 'disabled';
                                    $passportreadonly = 'readonly';
                                @endphp
                            @endif
                            <div class="emergency_details_form">
                                    <div class="two_input_field">
                                        <input type="text" {{ $passportreadonly }} name="passport_first_name" value="{{ $customer->passport_first_name ?? '' }}" autocomplete="given-name"  placeholder="FIRST NAME*"  >
                                        <input type="text" {{ $passportreadonly }} name="passport_last_name" value="{{ $customer->passport_last_name ?? '' }}" autocomplete="family-name" placeholder="LAST NAME*"  >
                                    </div>
                                    <div class="two_input_field mobile_number">
                                        <input type="text"  {{ $passportreadonly }} name="gender" value="{{ $customer->gender ?? '' }}" width="2" autocomplete="sex" placeholder="Gender" class="gender_input">
                                        <!-- <div class="second_mob_no"> -->
                                            <!-- <span><img src="/images/customer/images/aus_flag.svg" /><b>+61</b></span> -->
                                            <input type="tel" style="width: 100%;" {{ $passportreadonly }} id="passport_number" value="{{ $customer->passport_number ?? '' }}" placeholder="MOBILE NUMBER"  onchange="hideIcon(this);" class="phone-input mobile_no [&::-webkit-inner-spin-button]:appearance-none">
                                            <input type="hidden"  name="passport_number">

                                        <!-- </div> -->
                                    </div>
                                    <div class="one_input_field">
                                         <select name="passport_country" class="form-control" {{ $passportdisabled }}>
                                            <option value="">Select </option>
                                            @foreach(Country::all() as $country)
                                                <option value="{{ $country->id }}" {{ ($customer->passport_country_of_issue ?? '') == $country->id ? 'selected' : '' }}>
                                                    {{ strtoupper($country->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="two_input_field">
                                        <input type="date"  {{ $passportreadonly }} name="passport_issue_date" value="{{ $customer->passport_issue_date?->format('Y-m-d') ?? '' }}" placeholder="ISSUE DATE" onfocus="(this.type='date')"
                                    onblur="(this.type='text')"  >
                                    <input type="date" {{ $passportreadonly }} name="passport_expiry_date" value="{{ $customer->passport_expiry_date?->format('Y-m-d') ?? '' }}" placeholder="EXPIRY DATE" onfocus="(this.type='date')"
                                    onblur="(this.type='text')"  >
                                    </div>
                            </div>
                        </div>--}}
                        <hr/>
                        {{-- <div class="personal_details frequent_details" id="frequent_details">
                            <h3>Memberships</h3>
                            <div class="frequent_details_form">
                                <div class="one_input_field">
                                    <select name="airline_frequent_flyers_id" class="form-control">
                                        <option value="">Select Frequent Flyer</option>
                                        @foreach($frequentFlyers as $frequentFlyer)
                                            <option value="{{ $frequentFlyer->id }}" {{ ($customer->airline_frequent_flyers_id ?? '') == $frequentFlyer->id ? 'selected' : '' }}>
                                                {{ $frequentFlyer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="one_input_field"><input type="text" name="membership number" placeholder="MEMBERSHIP" value="{{ $customer->membership ?? '' }}"></div>
                            </div>
                        </div>
                        <hr/> --}}
                        <div class="personal_details loyalty_details" id="loyalty_details" style="margin-top:45px;">
                            <livewire:customer.loyalty-numbers :customer=$customer />
                        </div>
                        <hr/>
                        <div class="personal_details loyalty_details" id="frequent_details" style="margin-top:45px;">
                            <livewire:customer.merchandise :customer=$customer />
                        </div>
                        <hr/>
                        {{-- <div class="personal_details other_details" id="other_details">
                            <h3>Merchandise</h3>
                            <div class="other_details_form">
                                <div class="two_input_field">

                                    <input type="text" name="t_shirt_size_id" placeholder="T Shirt Size" value="{{ $customer->t_shirt_size }}">
                                    <input type="text" name="hat_size_id" placeholder="Hat Size" value="{{ $customer->hat_size }}">
                                    <input type="text" name="hat_size_id" placeholder="Loyalty Number" value="{{ $customer->loyalty_number }}">
<!-- <select name="t_shirt_size_id" class="form-control">
                                        <option value="">Select T Shirt Size</option>
                                        @foreach(TShirtSize::all() as $tsize)
                                            <option value="{{ $tsize->id }}" {{ ($customer->t_shirt_size_id ?? '') == $tsize->id ? 'selected' : '' }}>
                                                {{ strtoupper($tsize->name) }}
                                            </option>
                                        @endforeach
                                    </select> -->
                                        <!-- <select name="hat_size_id" class="form-control">
                                        <option value="">Select Hat Size</option>
                                        @foreach(HatSize::all() as $hsize)
                                            <option value="{{ $hsize->id }}" {{ ($customer->hat_size_id ?? '') == $hsize->id ? 'selected' : '' }}>
                                                {{ strtoupper($hsize->name) }}
                                            </option>
                                        @endforeach
                                    </select> -->
                                </div>
                            </div>
                        </div>
                        <hr/> --}}

                        {{--<div class="personal_details change_password" id="change_password">
                            <h3>Change Password</h3>
                            <div class="change_password_form">
                                <div class="two_input_field">
                                    <div class="current_pwd">
                                        <input type="password" name="current_password" width="4" autocomplete="current-password" placeholder="CURRENT PASSWORD">
                                        <span class="toggle-password" style="cursor:pointer;">
                                            <img src="/images/customer/images/eye-slash.svg" alt="Toggle Password">
                                        </span>
                                    </div>
                                </div>

                                <div class="two_input_field">
                                    <input type="password"  name="new_password" width="4" autocomplete="new-password" placeholder="NEW PASSWORD">
                                    <input type="password"  name="new_password_confirmation" autocomplete="new-password" placeholder="CONFIRM NEW PASSWORD" >
                                </div>
                            </div>
                        </div>

						</div>--}}
                    <div class="common_btn"><button type="submit" class="save_changes">Save Changes</button></div>
                </form>
                </div>
            </div>
        </div>
    </div>
<script>
    var billing_address_id  = '{{ $billing_address_id}}';
    var home_address_id     = '{{ $home_address_id}}';
    jQuery('.your_details_colm_1 li').click(function(e) {
        e.preventDefault();
        jQuery('.your_details_colm_1 li').removeClass('active');
        jQuery(this).addClass('active');
        const targetId = jQuery(this).find('a').attr('href');
        if (targetId && jQuery(targetId).length) {
            if (window.scrollY !== jQuery(targetId).offset().top) {
                document.querySelector(targetId).scrollIntoView({ behavior: 'smooth' });
            }
        }
    });
    // password show
     $('.toggle-password').on('click', function () {
        const input = $(this).siblings('input');
        const img = $(this).find('img');

        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            img.attr('src', '/images/customer/images/eye-slash.svg'); // eye open
        } else {
            input.attr('type', 'password');
            img.attr('src', '/images/customer/images/eye-slash.svg'); // eye slash
        }
    });
    // same address check
    if (home_address_id == billing_address_id) {
        $('#home_is_billing')
            .prop('checked', true);
        sameAddress($('#home_is_billing')[0]);
    }

    $('#home_is_billing').on('change', function () {
        console.log('retssad');
        sameAddress(this);
    });

    // If checkbox is already checked (on page load), copy addresses
    if ($('#home_is_billing').is(':checked')) {
        sameAddress($('#home_is_billing')[0]);
    }

    function sameAddress(checkbox) {
        if ($(checkbox).is(':checked')) {
            // Copy home address to billing address
            $('[name="billing_address_line_1"]').val($('[name="home_address_line_1"]').val());
            $('[name="billing_address_line_2"]').val($('[name="home_address_line_2"]').val());
            $('[name="billing_postcode"]').val($('[name="home_postcode"]').val());
            $('[name="billing_town"]').val($('[name="home_town"]').val());
            $('[name="billing_region"]').val($('[name="home_region"]').val());
            $('#billing_country_id').val($('#home_country_id').val());

            // Hide home address section
            $('#home-address-section').hide();
        } else {
            // Clear billing address
            $('[name="billing_address_line_1"]').val('');
            $('[name="billing_address_line_2"]').val('');
            $('[name="billing_postcode"]').val('');
            $('[name="billing_town"]').val('');
            $('[name="billing_region"]').val('');
            $('#billing_country_id').val('');

            // Show home address section
            $('#home-address-section').show();
        }
    }
    // same as address fetch

    $(document).ready(function () {

        // number formate with validation
        $('.phone-input').each(function () {
            const input = this;
            let defaultCountry = '';
            if ($(input).val().trim() !== '') {
                    defaultCountry = 'in';
            }
            const iti = window.intlTelInput(input, {
                initialCountry: defaultCountry, // blank initially
                preferredCountries: [],
                separateDialCode: true,
                nationalMode: false,
                autoPlaceholder: "On",
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            });

            if ($(input).val().trim() == '') {
                // --- Show "Choose Country" placeholder flag ---
                const flagContainer = $(input).closest('.iti').find('.iti__selected-flag');
                const placeholder = $('<div class="iti__flag iti__flag--placeholder"></div>');
                flagContainer.find('.iti__flag').hide();
                flagContainer.prepend(placeholder);
                flagContainer.find('.iti__selected-dial-code').text(''); // clear dial code
                flagContainer.attr('title', 'Select your country');

                // --- Track if first click happened ---
                let firstTimeClick = true;

                // Listen for country dropdown open (safe after plugin init)

              // Old code  // $(input).on('open:countrydropdown', function () {
                //     if (firstTimeClick) {
                //         $('.iti__country-list .iti__country').one('click', function () {
                //             flagContainer.find('.iti__flag').show();
                //             placeholder.hide();
                //             flagContainer.find('.choose-text').hide();
                //             firstTimeClick = false;
                //         });
                //     }
                // }); old code//

                $(input).on('open:countrydropdown', function () {
                    const countryList = $('.iti__country-list'); // dropdown element
                    if (countryList.find('.iti__choose-country').length === 0) {
                        // Add custom "Select your country" item only once
                        const chooseCountryItem = $(`
                            <li class="iti__country iti__choose-country" style="font-weight:bold; cursor:default;">

                                <span class="iti__country-name">Select your country</span>
                            </li>
                        `);
                        countryList.prepend(chooseCountryItem);
                    }
                });


            }
            // Also handle when user changes country (keyboard or code)
            $(input).on('countrychange', function () {
                flagContainer.find('.iti__flag').show();
                placeholder.hide();
            });

            // --- Update hidden input for valid numbers ---
            $(input).on('input change blur countrychange', function () {
                const id = $(this).attr('id');
                const hiddenInput = $('input[name="' + id + '"]');
                if (hiddenInput.length && iti.isValidNumber()) {
                    hiddenInput.val(iti.getNumber());
                } else {
                    hiddenInput.val('');
                }
            });

            // Save instance reference
            $(input).data('iti', iti);
        });
        // Validate on form submit
        $('#userform').on('submit', function (e) {
            let valid = true;

            $(this).find('input, select, textarea').each(function () {
                const input = $(this);
                const val = $.trim(input.val());

                removeError(input);

                if (input.prop('disabled') || input.prop('readonly') || input.attr('type') === 'hidden') return;

                // Required field
                if (input.prop('required') && val === '') {
                    showError(input, 'This field is required.');
                    valid = false;
                    return;
                }

                // Email validation
                if (input.attr('type') === 'email' && val !== '') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(val)) {
                        showError(input, 'Enter a valid email.');
                        valid = false;
                        return;
                    }
                }

                // Phone validation
                if (input.hasClass('phone-input') && val !== '') {
                    const iti = input.data('iti');
                    if (iti && !iti.isValidNumber()) {
                        showError(input, 'Enter a valid phone number.');
                        valid = false;
                        return;
                    }
                }
            });

            if (!valid) e.preventDefault();
        });

        // Remove error on input
        $('#userform input, #userform select, #userform textarea').on('input change', function () {
            removeError($(this));
        });

        // Show error message
        function showError(input, message) {
            input.css('border', '1px solid red');
            if (!input.next('.input-error-message').length) {
                $('<span class="input-error-message" style="color:red; font-size:0.9em;">' + message + '</span>').insertAfter(input);
            }
        }

        // Remove error message
        function removeError(input) {
            input.css('border', '');
            input.next('.input-error-message').remove();
        }
    });



</script>


@endsection
