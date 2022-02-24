@extends('layout.customer')

@section('title', 'Edit Customer Profile')

@section('content')  
<div class="container-fluid">    
    <div class="row">
        <!-- Column -->
        <div class="col-lg-3 col-xxl-2 col-md-4">
            <div class="card">
                <div class="card-body profile-card">
                    <center class="mt-4">
                        <div class="overlay-container">
                            <label for="profile_picture">
                                <img src="{{ asset($customer->profile_picture) }}" class="rounded-circle hover-upload" width="150" />
                                <div class="image-overlay">Upload new picture</div>
                            </label>
                        </div>
                        <h4 class="card-title mt-2">{{ $customer->title }} {{ $customer->first_name }} {{ $customer->middle_names }} {{ $customer->last_name }}</h4>
                        <h6 class="card-subtitle">{{ $customer->email_address }}</h6>                        
                    </center>
                </div>
            </div>
        </div>
        <!-- Column -->
        <!-- Column -->
        <div class="col-lg-9 col-xxl-10 col-md-8">
            <div class="card">
                <div class="card-body">
                    <form class="form-horizontal form-material mx-2 row" action="{{ route('customer.update') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="profile_picture" id="profile_picture" style="display: none;" onchange="form.submit()">
                        <hr class="splitter">
                        <div class="form-group col-md-6">
                            <h4 class="col-md-12 mb-0">Basic Information</h4>
                        </div>
                        <hr class="splitter">
                        <div class="form-group col-md-1">
                            <label class="col-md-12 mb-0">Title</label>
                            <div class="col-md-12">
                                <input type="text" name="title" id="title-input" value="{{ $customer->title ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="col-md-12 mb-0">First Name</label>
                            <div class="col-md-12">
                                <input type="text" name="first_name" id="first_name-input" value="{{ $customer->first_name ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Middle Names</label>
                            <div class="col-md-12">
                                <input type="text" name="middle_names" id="middle_names-input" value="{{ $customer->middle_names ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Last Name</label>
                            <div class="col-md-12">
                                <input type="text" name="last_name" id="last_name-input" value="{{ $customer->last_name ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Date of Birth</label>
                            <div class="col-md-12">
                                <input type="date" name="date_of_birth" id="date_of_birth-input" value="{{ $customer->date_of_birth->format('Y-m-d') ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Mobile Number</label>
                            <div class="col-md-12">
                                <input type="text" name="mobile_number" id="mobile_number-input" value="{{ $customer->mobile_number ?? '' }}"
                                       class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Other Phone Number</label>
                            <div class="col-md-12">
                                <input type="text" name="other_phone_number" id="other_phone_number-input" value="{{ $customer->other_phone_number ?? '' }}"
                                       class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <hr class="splitter">
                        <div class="form-group col-md-6">
                            <h4 class="col-md-12 mb-0">Home Address</h4>
                        </div>
                        <div class="form-group col-md-6">
                            <h4 class="col-md-12 mb-0">Billing Address</h4>
                        </div>
                        <hr class="splitter">
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Address Line 1</label>
                            <div class="col-md-12">
                                <input type="text" name="home_address_line_1" id="home_address_line_1-input" value="{{ $customer->homeAddress->address_line_1 ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Address Line 1</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_address_line_1" id="billing_address_line_1-input" value="{{ $customer->billingAddress->address_line_1 ?? '' }}"
                                       class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Address Line 2</label>
                            <div class="col-md-12">
                                <input type="text" name="home_address_line_2" id="home_address_line_2-input" value="{{ $customer->homeAddress->address_line_2 ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Address Line 2</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_address_line_2" id="billing_address_line_2-input" value="{{ $customer->billingAddress->address_line_2 ?? '' }}"
                                       class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Town</label>
                            <div class="col-md-12">
                                <input type="text" name="home_town" id="home_town-input" value="{{ $customer->homeAddress->town ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Town</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_town" id="billing_town-input" value="{{ $customer->billingAddress->town ?? '' }}"
                                       class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Region</label>
                            <div class="col-md-12">
                                <input type="text" name="region" id="region-input" value="{{ $customer->homeAddress->region ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Region</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_region" id="billing_region-input" value="{{ $customer->billingAddress->region ?? '' }}"
                                       class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Country</label>
                            <div class="col-md-12">
                                <input type="text" name="home_country" id="home_country-input" value="{{ $customer->homeAddress->country ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Country</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_country" id="billing_country-input" value="{{ $customer->billingAddress->country ?? '' }}"
                                       class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Postcode</label>
                            <div class="col-md-12">
                                <input type="text" name="home_postcode" id="home_postcode-input" value="{{ $customer->homeAddress->postcode ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Postcode</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_postcode" id="billing_postcode-input" value="{{ $customer->billingAddress->postcode ?? '' }}"
                                       class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <hr class="splitter">
                        <div class="form-group">
                            <h4 class="col-md-12 mb-0">Emergency Contact Details</h4>
                        </div>
                        <hr class="splitter">
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Name</label>
                            <div class="col-md-12">
                                <input type="text" name="emergency_contact_name" id="emergency_contact_name-input" value="{{ $customer->emergency_contact_name ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-6 mb-0">Relationship</label>
                            <div class="col-md-12">
                                <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship-input" value="{{ $customer->emergency_contact_relationship ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Telephone</label>
                            <div class="col-md-12">
                                <input type="text" name="emergency_contact_telephone" id="emergency_contact_telephone-input" value="{{ $customer->emergency_contact_telephone ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <hr class="splitter">
                        <div class="form-group">
                            <h4 class="col-md-12 mb-0">Passport Details</h4>
                        </div>
                        <hr class="splitter">
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">First Name</label>
                            <div class="col-md-12">
                                <input type="text" name="passport_first_name" id="passport_first_name-input" value="{{ $customer->passport_first_name ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Middle Name</label>
                            <div class="col-md-12">
                                <input type="text" name="passport_middle_name" id="passport_middle_name-input" value="{{ $customer->passport_middle_name ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Last Name</label>
                            <div class="col-md-12">
                                <input type="text" name="passport_last_name" id="passport_last_name-input" value="{{ $customer->passport_last_name ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Gender</label>
                            <div class="col-md-12">
                                <input type="text" name="gender" id="gender-input" value="{{ $customer->gender ?? '' }}"
                                       class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Number</label>
                            <div class="col-md-12">
                                <input type="text" name="passport_number" id="passport_number-input" value="{{ $customer->passport_number ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Expiry Date</label>
                            <div class="col-md-12">
                                <input type="date" name="passport_expiry_date" id="passport_expiry_date-input" value="{{ $customer->passport_expiry_date->format('Y-m-d') ?? '' }}"
                                    class="form-control ps-0 form-control-line">
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
