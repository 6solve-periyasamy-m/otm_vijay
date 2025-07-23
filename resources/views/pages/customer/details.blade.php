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
    .iti__selected-flag {
    z-index: 1;
    position: relative;
    display: flex;
    align-items: center;
    height: 100%;
    padding: 4px 0px 14px 7px !important
}
.two_input_field.mobile_number .iti{width: 50%;}
.two_input_field.mobile_number .iti--separate-dial-code .iti__selected-flag{background: transparent;padding-left: 24px !important;padding-bottom: 7px !important;}
.two_input_field.mobile_number .iti__arrow {
    width: 1px;
    height: 17px;
    display: inline-block;
    background: #F35B15;
    border: unset !important;
    margin-left: 7px;
}
.two_input_field.mobile_number .iti__selected-dial-code {
    font-family: "PP Neue Montreal" !important;
    font-size: 14px !important;
    line-height: 24px !important;
    color: #721111 !important;
}
.two_input_field.mobile_number ul li::after{display: none;}
.two_input_field.mobile_number ul li{padding: 5px 10px;border-bottom: 0px !important;}
.two_input_field.mobile_number ul li.iti__divider{padding:0px;border-bottom: 0px;}
.two_input_field.mobile_number .iti__country-name {
    font-family: "PP Neue Montreal";
    font-size: 14px;
    line-height: 26px;
    padding-right: 10px;
    margin-left: 10px;
    color: #000;
    text-transform: capitalize;
}
.two_input_field.mobile_number .iti__country-list{max-width: 340px;overflow-x: hidden;background-color:#F9F4EE;}
.personal_details input{padding-right: 20px;}
#mobile_number:focus{box-shadow: unset;}
.two_input_field.mobile_number .iti__country-list::-webkit-scrollbar{width:5px;}
.two_input_field.mobile_number .iti__country-list::-webkit-scrollbar-thumb {
    background: #F35B15;
    border-radius: 10px;
}
.iti--allow-dropdown input[type=tel], .iti--separate-dial-code input,.iti--allow-dropdown input{padding-left: 65px;}
</style>
@section('content')
    {{--  <div class="container-fluid">
        <div class="row">
            <!-- Column -->
            <div class="col-lg-3 col-xxl-2 col-md-4">
                <div class="card">
                    <div class="card-body profile-card">
                        <center class="mt-4">
                            <div class="overlay-container">
                                <label for="profile_picture">
                                    <img src="{{ $customer->avatar_url }}" class="rounded-circle hover-upload" alt="{{ $customer->full_name }}"/>
                                    <div class="image-overlay">Upload new picture</div>
                                </label>
                            </div>
                            <h4 class="card-title mt-2">{{ $customer->first_name }} {{ $customer->last_name }}</h4>
                            <h6 class="card-subtitle">{{ $customer->email_address }}</h6>
                        </center>
                    </div>
                </div>
                @if(sizeof($editable ?? []) > 0 || !$self)
                    <div class="accordion" id="accordionCustomers">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingCustomers">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Additional Customers
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingCustomers"
                                 data-bs-parent="#accordionCustomers">
                                <div class="accordion-body">
                                    @if(!$self)
                                        <div class="card other-profile"
                                             onclick="window.location = '{{ route('customer.edit') }}';">
                                            <div class="card-body profile-card">
                                                <center class="mt-4">
                                                    <h4 class="card-title mt-2 additional-customer-title">{{ $currentUser->first_name }} {{ $currentUser->last_name }}</h4>
                                                    <h6 class="card-subtitle additional-customer-subtitle">{{ $currentUser->email_address }}</h6>
                                                </center>
                                            </div>
                                        </div>
                                    @endif
                                    @foreach($editable as $editee)
                                        @if($editee->id === $customer->id)
                                            @continue
                                        @endif
                                        <div class="card other-profile"
                                             onclick="window.location = '{{ route('customer.edit.other', ['customer' => $editee,]) }}';">
                                            <div class="card-body profile-card">
                                                <center class="mt-4">
                                                    <h4 class="card-title mt-2 additional-customer-title">{{ $editee->first_name }} {{ $editee->last_name }}</h4>
                                                    <h6 class="card-subtitle additional-customer-subtitle">{{ $editee->email_address }}</h6>
                                                </center>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <!-- Column -->
            <!-- Column -->
            <div class="col-lg-9 col-xxl-10 col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form class="form-horizontal form-material mx-2 row"
                              @if (!isset($other))
                                  action="{{ route('customer.update') }}"
                              @else
                                  action="{{ route('customer.update.other', ['customer' => $customer,]) }}"
                              @endif
                              method="post" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="profile_picture" id="profile_picture" style="display: none;"
                                   onchange="form.submit()">

                            <hr class="splitter">
                            <div class="form-group"><h4 class="mb-0">Basic Information</h4></div>
                            <hr class="splitter">

                            <x-customer.input name="title" value="{{ $customer->title ?? '' }}" width="2" autocomplete="honorific-prefix" required>
                                Title
                            </x-customer.input>

                            <x-customer.input name="first_name" value="{{ $customer->first_name ?? '' }}" width="3" autocomplete="given-name" required>
                                First Name
                            </x-customer.input>

                            <x-customer.input name="middle_names" value="{{ $customer->middle_names ?? '' }}" width="3" autocomplete="additional-name">
                                Middle Names
                            </x-customer.input>

                            <x-customer.input name="last_name" value="{{ $customer->last_name ?? '' }}" width="4" autocomplete="family-name" required>
                                Last Name
                            </x-customer.input>

                            <x-customer.input type="date" name="date_of_birth" value="{{ $customer->date_of_birth?->format('Y-m-d') ?? '' }}" width="4" autocomplete="bday" required>
                                Date of Birth
                            </x-customer.input>

                            <x-customer.input name="mobile_number" value="{{ $customer->mobile_number ?? '' }}" width="4" autocomplete="tel">
                                Mobile Number
                            </x-customer.input>

                            <x-customer.input name="other_phone_number" value="{{ $customer->other_phone_number ?? '' }}" width="4">
                                Other Phone Number
                            </x-customer.input>

                            <hr class="splitter">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4 class="mb-0">Home Address</h4>
                                </div>
                                <hr class="splitter">

                                <x-customer.input name="home_address_line_1" value="{{ $customer->homeAddress->address_line_1 ?? '' }}" autocomplete="address-line1">
                                    Address Line 1
                                </x-customer.input>

                                <x-customer.input name="home_address_line_2" value="{{ $customer->homeAddress->address_line_2 ?? '' }}" autocomplete="address-line2">
                                    Address Line 2
                                </x-customer.input>
                                <x-customer.input name="home_town" value="{{ $customer->homeAddress->town ?? '' }}" autocomplete="address-level2">
                                    Town
                                </x-customer.input>
                                <x-customer.input name="home_region" value="{{ $customer->homeAddress->region ?? '' }}" autocomplete="address-level1">
                                    Region
                                </x-customer.input>

                                @include('partials.fields.selector.default', ['name' => 'Country', 'field' => 'home_country_id', 'value' => $customer->homeAddress->country_id ?? null, 'width' => 6, 'route' => 'countries', 'divClasses' => 'w-100'])

                                <x-customer.input name="home_postcode" value="{{ $customer->homeAddress->postcode ?? '' }}" autocomplete="postcode">
                                    Postcode
                                </x-customer.input>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <h4 class="mb-0">Billing Address</h4>
                                </div>
                                <hr class="splitter">

                                <x-customer.input name="billing_address_line_1" value="{{ $customer->billingAddress->address_line_1 ?? '' }}" autocomplete="address-line1">
                                    Address Line 1
                                </x-customer.input>

                                <x-customer.input name="billing_address_line_2" value="{{ $customer->billingAddress->address_line_2 ?? '' }}" autocomplete="address-line2">
                                    Address Line 2
                                </x-customer.input>

                                <x-customer.input name="billing_town" value="{{ $customer->billingAddress->town ?? '' }}" autocomplete="address-level2">
                                    Town
                                </x-customer.input>
                                <x-customer.input name="billing_region" value="{{ $customer->billingAddress->region ?? '' }}" autocomplete="address-level1">
                                    Region
                                </x-customer.input>

                                @include('partials.fields.selector.default', ['name' => 'Country', 'field' => 'billing_country_id', 'value' => $customer->billingAddress->country_id ?? null, 'width' => 6, 'route' => 'countries', 'divClasses' => 'w-100'])

                                <x-customer.input name="billing_postcode" value="{{ $customer->billingAddress->postcode ?? '' }}" autocomplete="postcode">
                                    Postcode
                                </x-customer.input>
                            </div>
                            <hr class="splitter">
                            <div class="form-group"><h4 class="mb-0">Emergency Contact Details</h4></div>
                            <hr class="splitter">

                            <x-customer.input name="emergency_contact_name" value="{{ $customer->emergency_contact_name ?? '' }}" width="4">
                                Name
                            </x-customer.input>

                            <x-customer.input name="emergency_contact_relationship" value="{{ $customer->emergency_contact_relationship ?? '' }}" width="4">
                                Relationship
                            </x-customer.input>

                            <x-customer.input name="emergency_contact_telephone" value="{{ $customer->emergency_contact_telephone ?? '' }}" width="4">
                                Telephone
                            </x-customer.input>

                            <hr class="splitter">
                            <div class="form-group">
                                <h4 class="mb-0">Passport Details</h4>
                            </div>
                            <hr class="splitter">
                            @if ($passport)
                                <span class="passport_dtls">Passport details are currently locked due to an upcoming tour. If you need to update your passport details, please contact us.</span>
                            @endif
                            <x-customer.input :disable="$passport" name="passport_first_name" value="{{ $customer->passport_first_name ?? '' }}" width="4" autocomplete="given-name">
                                First Name
                            </x-customer.input>

                            <x-customer.input :disable="$passport" name="passport_middle_name" value="{{ $customer->passport_middle_name ?? '' }}" width="4" autocomplete="additional-name">
                                Middle Name
                            </x-customer.input>

                            <x-customer.input :disable="$passport" name="passport_last_name" value="{{ $customer->passport_last_name ?? '' }}" width="4" autocomplete="family-name">
                                Last Name
                            </x-customer.input>

                            <x-customer.input :disable="$passport" name="gender" value="{{ $customer->gender ?? '' }}" width="2" autocomplete="sex">
                                Gender
                            </x-customer.input>

                            <x-customer.input :disable="$passport" name="passport_number" value="{{ $customer->passport_number ?? '' }}" width="2">
                                Number
                            </x-customer.input>

                            <x-customer.input :disable="$passport" name="passport_country" value="{{ $customer->passport_country_of_issue ?? '' }}" width="4">
                                Country of Issue
                            </x-customer.input>

                            <x-customer.input :disable="$passport" type="date" name="passport_issue_date" value="{{ $customer->passport_issue_date?->format('Y-m-d') ?? '' }}" width="2">
                                Issue Date
                            </x-customer.input>

                            <x-customer.input :disable="$passport" type="date" name="passport_expiry_date" value="{{ $customer->passport_expiry_date?->format('Y-m-d') ?? '' }}" width="2">
                                Expiry Date
                            </x-customer.input>
                            <hr class="splitter">
                            <div class="form-group">
                                <h4 class="mb-0">Merchandise Clothing Sizes</h4>
                            </div>
                            <hr class="splitter">

                            @include('partials.fields.selector.default',
                                ['name' => 'T-Shirt Size', 'field' => 't_shirt_size_id', 'value' => $customer->t_shirt_size_id ?? 0, 'route' => 't-shirt-size', 'width' => 6])
                            @include('partials.fields.selector.default',
                                ['name' => 'Hat Size', 'field' => 'hat_size_id', 'value' => $customer->hat_size_id ?? 0, 'route' => 'hat-size', 'width' => 6])

                            <hr class="splitter">

                            <x-customer.input.text-area name="dietary_notes" value="{{ $customer->dietary_notes }}">
                                Dietary Requirements
                            </x-customer.input.text-area>

                            <x-customer.input.text-area name="mobility_notes" value="{{ $customer->mobility_notes }}">
                                Mobility Requirements
                            </x-customer.input.text-area>

                            <x-customer.input.text-area name="other_notes" value="{{ $customer->external_notes }}">
                                Other Notes
                            </x-customer.input.text-area>

                            <hr class="splitter">

                            @if(!isset($other))
                                <div class="form-group">
                                    <h4 class="col-md-12 mb-0">Change your password</h4>
                                </div>
                                <hr class="splitter">

                                <x-customer.input type="password" name="current_password" width="4" autocomplete="current-password">
                                    Current Password
                                </x-customer.input>

                                <x-customer.input type="password" name="new_password" width="4" autocomplete="new-password">
                                    New Password
                                </x-customer.input>

                                <x-customer.input type="password" name="new_password_confirmation" width="4" autocomplete="new-password">
                                    Confirm your new password
                                </x-customer.input>

                                <hr class="splitter">
                            @endif
                            <div class="form-group">
                                <div class="col-sm-12 d-flex">
                                    <button type="submit" class="btn btn-success mx-md-0 text-white">
                                        Update Profile
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="inner_content">
        <x-customer.overview-top-bar title="Your Details" :search="false" />
        <div class="your_details">
            <div class="your_details_row">
                <div class="your_details_colm_1">
                    <ul>
                        <li class="active"><a href="#personal_details">Personal Details</a></li>
                        <li><a href="#address_details">Address</a></li>
                        <li><a href="#passport_details">Passport Details</a></li>
                        <li><a href="#frequent_details">Frequent Flier Details</a></li>
                        <li><a href="#other_details">Other Details</a></li>
                        <li><a href="#change_password">Change Password</a></li>
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
                                <div class="one_input_field"><input type="text" name="title" value="{{ $customer->title ?? '' }}" autocomplete="honorific-prefix" required ></div>
                                <div class="two_input_field">
                                    <input type="text" name="first_name" value="{{ $customer->first_name ?? '' }}"  autocomplete="given-name" placeholder="First Name *" required>
                                    <input type="text" name="last_name" value="{{ $customer->last_name ?? '' }}"  autocomplete="family-name"  placeholder="Last Name *" required>
                                </div>
                                <div class="one_input_field">
                                </div>
                                <div class="two_input_field">  
                                    <input placeholder="DATE OF BIRTH*" onfocus="(this.type='date')"
                                    onblur="(this.type='text')" type="date" name="date_of_birth" value="{{ $customer->date_of_birth?->format('Y-m-d') ?? '' }}" width="4" autocomplete="bday" required > 
                                    <input type="email" placeholder="EMAIL ADDRESS" name="email_address" value="{{ $customer->email_address ?? '' }}">
                                </div>
                                <div class="two_input_field mobile_number">
                                    <input style="width: 100%;" type="tel" id="mobile_number" value="{{ old('mobile_number', $customer->mobile_number ?? '') }}" class=" phone-input form-control" required>
                                    <input type="hidden" name="mobile_number" id="mobile_number"  value="{{ old('mobile_number', $customer->mobile_number ?? '') }}">
                                    <span  style="color:red; display:none;">Please enter a valid phone number.</span>                                      
                                    <input type="tel" id="other_phone_number" value="{{ $customer->other_phone_number ?? '' }}" placeholder="ALTERNATE MOBILE NUMBER"  class="phone-input alternate_no mobile_no [&::-webkit-inner-spin-button]:appearance-none"  style="width: 100%;">                                        
                                    <input type="hidden" name="other_phone_number" value="{{ $customer->other_phone_number ?? '' }}">
                                    <span  style="color:red; display:none;">Please enter a valid phone number.</span>
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
                                    <div class="one_input_field"><input type="text" name="home_address_line_1" value="{{ $customer->homeAddress->address_line_1 ?? '' }}" autocomplete="address-line1"placeholder="ADDRESS LINE 1" required ></div>
                                    <div class="one_input_field"><input type="text" name="home_address_line_2" value="{{ $customer->homeAddress->address_line_2 ?? '' }}" autocomplete="address-line2" placeholder="ADDRESS LINE 2" required ></div>
                                    <div class="one_input_field"><input type="text" name="home_postcode" value="{{ $customer->homeAddress->postcode ?? '' }}" autocomplete="postcode" placeholder="POST CODE" required ></div>
                                    <div class="one_input_field"><input type="text" name="home_town" value="{{ $customer->homeAddress->town ?? '' }}" autocomplete="address-level2"placeholder="TOWN" required ></div>
                                    <div class="one_input_field"><input type="text" name="home_region" value="{{ $customer->homeAddress->region ?? '' }}" autocomplete="address-level1" placeholder="REGION" required ></div>
                                    <div class="one_input_field">
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
                                            <span>Same as Billing</span>
                                            
                                            </label>
                                    </span>
                                    </div>
                                    <div class="one_input_field"><input type="text"  name="billing_address_line_1" value="{{ $customer->billingAddress->address_line_1 ?? '' }}" autocomplete="address-line1" placeholder="ADDRESS LINE 1" ></div>
                                    <div class="one_input_field"><input type="text" name="billing_address_line_2" value="{{ $customer->billingAddress->address_line_2 ?? '' }}" autocomplete="address-line2" placeholder="ADDRESS LINE 2"  ></div>
                                    <div class="one_input_field"><input type="text" name="billing_postcode" value="{{ $customer->billingAddress->postcode ?? '' }}" autocomplete="postcode" placeholder="POST CODE" required ></div>
                                    <div class="one_input_field"><input type="text" name="billing_town" value="{{ $customer->billingAddress->town ?? '' }}" autocomplete="address-level2" placeholder="TOWN" ></div>
                                    <div class="one_input_field"><input type="text" name="billing_region" value="{{ $customer->billingAddress->region ?? '' }}" autocomplete="address-level1" placeholder="REGION" ></div>
                                    <div class="one_input_field">
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
                                <div class="two_input_field">
                                    <input type="text" placeholder="Contact NAME*" name="emergency_contact_name" value="{{ $customer->emergency_contact_name ?? '' }}"  required>
                                </div>
                                <div class="two_input_field mobile_number">
                                    <input type="text" name="emergency_contact_relationship" value="{{ $customer->emergency_contact_relationship ?? '' }}" placeholder="RELATIONSHIP" class="relationship_input" required>
                                    <input style="width: 100%;" type="tel" id="emergency_contact_telephone" value="{{ $customer->emergency_contact_telephone ?? '' }}" placeholder="MOBILE NUMBER" required   class=" phone-input mobile_no [&::-webkit-inner-spin-button]:appearance-none">
                                    <input type="hidden" name="emergency_contact_telephone" value="{{ $customer->emergency_contact_telephone ?? '' }}" >
                                </div>                                    
                            </div>
                        </div>
                        <hr/>
                        <div class="personal_details passport_details" id="passport_details">
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
                        </div>
                        <hr/>
                        <div class="personal_details frequent_details" id="frequent_details">
                            <h3>Frequent Flyer Details</h3>
                            <div class="frequent_details_form">
                                    <div class="one_input_field">
                                        <select name="program">
                                            <option value="program1">PROGRAM </option>
                                            <option value="program2">PROGRAM 2</option>
                                            <option value="program3">PROGRAM 3</option>
                                            <option value="program4">PROGRAM 4</option>
                                          </select>
                                    </div>
                                    <div class="one_input_field"><input type="text" name="membership number" placeholder="MEMBERSHIP NUMBER" ></div>
                            </div>
                        </div>
                        <hr/>
                        <div class="personal_details other_details" id="other_details">
                            <h3>Other Details</h3>
                            <div class="other_details_form">
                                    <div class="two_input_field">
                                         <select name="t_shirt_size_id" class="form-control">
                                            <option value="">Select T Shirt Size</option>
                                            @foreach(TShirtSize::all() as $tsize)
                                                <option value="{{ $tsize->id }}" {{ ($customer->t_shirt_size_id ?? '') == $tsize->id ? 'selected' : '' }}>
                                                    {{ strtoupper($tsize->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                         <select name="hat_size_id" class="form-control">
                                            <option value="">Select Hat Size</option>
                                            @foreach(HatSize::all() as $hsize)
                                                <option value="{{ $hsize->id }}" {{ ($customer->hat_size_id ?? '') == $hsize->id ? 'selected' : '' }}>
                                                    {{ strtoupper($hsize->name) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="one_input_field"><input type="text" name="other_notes" value="{{ $customer->external_notes }}"  placeholder="Other Notes"  ></div>
                                    <div class="one_input_field"><input type="text"  name="dietary_notes" value="{{ $customer->dietary_notes }}" placeholder="Dietary Requirements"  ></div>
                                    <div class="one_input_field"><input type="text" name="mobility_notes" value="{{ $customer->mobility_notes }}" placeholder="Mobility Requirements" ></div>
                            </div>
                        </div>
                        <hr/>
                        <div class="personal_details change_password" id="change_password">
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

                    </div>
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
            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                geoIpLookup: function(callback) {
                    $.get('https://ipapi.co/json', function() {}, "json").always(function(resp) {
                    var countryCode = (resp && resp.country_code) ? resp.country_code : "us";
                    callback(countryCode);
                });
            },
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                separateDialCode: true,
            });

                // Save instance for later use
            $(input).data('iti', iti);

            // Set formatted number to hidden input whenever the phone input changes
            $(input).on('input change blur', function () {
                const id = $(this).attr('id'); // Get the ID of the phone input
                const hiddenInput = $('input[name="' + id + '"]'); // Find hidden input with matching name
                if (hiddenInput.length && iti.isValidNumber()) {
                    hiddenInput.val(iti.getNumber()); // Set the full international number (E.164 format)
                }
            });
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
