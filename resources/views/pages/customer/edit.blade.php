@extends('layout.customer')

@section('title', 'Edit Customer Profile')

@section('content')  
<div class="container-fluid">    
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-xxl-3 col-md-5">
            <div class="card">
                <div class="card-body profile-card">
                    <center class="mt-4"> 
                        <img src="{{ asset($customer->profile_picture) }}" class="rounded-circle" width="150" />
                        <h4 class="card-title mt-2">{{ $customer->title }} {{ $customer->first_name }} {{ $customer->middle_names }} {{ $customer->last_name }}</h4>
                        <h6 class="card-subtitle">{{ $customer->email_address }}</h6>                        
                    </center>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-8 col-xxl-9 col-md-7">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal form-material mx-2" action="{{ route('customers.update', ['customer' => $customer,]) }}">
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Title</label>
                            <div class="col-md-12">
                                <input type="text" name="title" id="title-input" value="{{ $customer->title ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">First Name</label>
                            <div class="col-md-12">
                                <input type="text" name="first_name" id="first_name-input" value="{{ $customer->first_name ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Middle Names</label>
                            <div class="col-md-12">
                                <input type="text" name="middle_names" id="middle_names-input" value="{{ $customer->middle_names ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Last Name</label>
                            <div class="col-md-12">
                                <input type="text" name="last_name" id="last_name-input" value="{{ $customer->last_name ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Gender</label>
                            <div class="col-md-12">
                                <input type="text" name="gender" id="gender-input" value="{{ $customer->gender ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Date of Birth</label>
                            <div class="col-md-12">
                                <input type="date" name="date_of_birth" id="date_of_birth-input" value="{{ $customer->date_of_birth ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Profile Picture</label>
                            <div class="col-md-12">
                                <input type="file" name="profile_picture" id="profile_picture-input" 
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>                        
                        <br><br>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Email Address</label>
                            <div class="col-md-12">
                                <input type="email" name="email_address" id="email_address-input" value="{{ $customer->email_address ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Password</label>
                            <div class="col-md-12">
                                <input type="password" name="password" id="password-input"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Home Address Line 1</label>
                            <div class="col-md-12">
                                <input type="text" name="home_address_line_1" id="home_address_line_1-input" value="{{ $customer->homeAddress->address_line_1 ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Home Address Line 2</label>
                            <div class="col-md-12">
                                <input type="text" name="home_address_line_2" id="home_address_line_2-input" value="{{ $customer->homeAddress->address_line_2 ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Home Town</label>
                            <div class="col-md-12">
                                <input type="text" name="home_town" id="home_town-input" value="{{ $customer->homeAddress->town ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Home Region</label>
                            <div class="col-md-12">
                                <input type="text" name="region" id="region-input" value="{{ $customer->homeAddress->region ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Home Country</label>
                            <div class="col-md-12">
                                <input type="text" name="home_country" id="home_country-input" value="{{ $customer->homeAddress->country ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Home Postcode</label>
                            <div class="col-md-12">
                                <input type="text" name="home_postcode" id="home_postcode-input" value="{{ $customer->homeAddress->postcode ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" name="home_is_billing" class="form-check-input"
                                @if(isset($home_address_id) && isset($billing_address_id) && $home_address_id == $billing_address_id) checked @endif
                            id="home_is_billing-input" onchange="changeBillingForm()">
                            <label for="home_is_billing-input" class="form-check-label">Billing Address is Same As Home</label>
                        </div>
                        <br><br>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Billing Address Line 1</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_address_line_1" id="billing_address_line_1-input" value="{{ $customer->billingAddress->address_line_1 ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Billing Address Line 2</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_address_line_2" id="billing_address_line_2-input" value="{{ $customer->billingAddress->address_line_2 ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Billing Town</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_town" id="billing_town-input" value="{{ $customer->billingAddress->town ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Billing Region</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_region" id="billing_region-input" value="{{ $customer->billingAddress->region ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Billing Country</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_country" id="billing_country-input" value="{{ $customer->billingAddress->country ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Billing Postcode</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_postcode" id="billing_postcode-input" value="{{ $customer->billingAddress->postcode ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Mobile Number</label>
                            <div class="col-md-12">
                                <input type="text" name="mobile_number" id="mobile_number-input" value="{{ $customer->mobile_number ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Other Phone Number</label>
                            <div class="col-md-12">
                                <input type="text" name="other_phone_number" id="other_phone_number-input" value="{{ $customer->other_phone_number ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Emergency Contact Name</label>
                            <div class="col-md-12">
                                <input type="text" name="emergency_contact_name" id="emergency_contact_name-input" value="{{ $customer->emergency_contact_name ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Emergency Contact Relationship</label>
                            <div class="col-md-12">
                                <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship-input" value="{{ $customer->emergency_contact_relationship ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Emergency Contact Telephone</label>
                            <div class="col-md-12">
                                <input type="text" name="emergency_contact_telephone" id="emergency_contact_telephone-input" value="{{ $customer->emergency_contact_telephone ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Passport First Name</label>
                            <div class="col-md-12">
                                <input type="text" name="passport_first_name" id="passport_first_name-input" value="{{ $customer->passport_first_name ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Passport Middle Name</label>
                            <div class="col-md-12">
                                <input type="text" name="passport_middle_name" id="passport_middle_name-input" value="{{ $customer->passport_middle_name ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Passport Last Name</label>
                            <div class="col-md-12">
                                <input type="text" name="passport_last_name" id="passport_last_name-input" value="{{ $customer->passport_last_name ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Passport Number</label>
                            <div class="col-md-12">
                                <input type="text" name="passport_number" id="passport_number-input" value="{{ $customer->passport_number ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Passport Issue Date</label>
                            <div class="col-md-12">
                                <input type="text" name="passport_expiry_date" id="passport_expiry_date-input" value="{{ $customer->passport_expiry_date ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="example-email" class="col-md-12">Loyalty Number</label>
                            <div class="col-md-12">
                                <input type="text" name="loyalty_number" id="loyalty_number-input" value="{{ $customer->loyalty_number ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>                      
                        <div class="form-group">
                            <label class="col-md-12 mb-0">Notes</label>
                            <div class="col-md-12">
                                <textarea rows="5" class="form-control ps-0 form-control-line" name="notes" id="notes-id">{{ $notes ?? "" }}</textarea>
                            </div>
                        </div>                        
                        <div class="form-group">
                            <div class="col-sm-12 d-flex">
                                <button type="submit" class="btn btn-success mx-auto mx-md-0 text-white">
                                    Update Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>                    
    </div>               
</div>            
@endsection
