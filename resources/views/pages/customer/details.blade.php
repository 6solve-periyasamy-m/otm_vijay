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
                        <h4 class="card-title mt-2">{{ $customer->first_name }} {{ $customer->last_name }}</h4>
                        <h6 class="card-subtitle">{{ $customer->email_address }}</h6>                        
                    </center>
                </div>
            </div>
            <div class="accordion" id="accordionExample">
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Additional Customers
                  </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                      @if(\App\Repository\CustomerAuthenticationRepository::getCustomer()->id !== $customer->id)
                          <div class="card other-profile" onclick="window.location = '{{ route('customer.edit') }}';">
                              <div class="card-body profile-card">
                                  <center class="mt-4">
                                      <h4 class="card-title mt-2 additional-customer-title">{{ \App\Repository\CustomerAuthenticationRepository::getCustomer()->first_name }} {{ \App\Repository\CustomerAuthenticationRepository::getCustomer()->last_name }}</h4>
                                      <h6 class="card-subtitle">{{ \App\Repository\CustomerAuthenticationRepository::getCustomer()->email_address }}</h6>
                                  </center>
                              </div>
                          </div>
                      @endif
                      @foreach($editable as $editee)
                          @if($editee->id === $customer->id) @continue @endif
                          <div class="card other-profile" onclick="window.location = '{{ route('customer.edit.other', ['customer' => $editee,]) }}';">
                              <div class="card-body profile-card">
                                  <center class="mt-4">
                                      <h4 class="card-title mt-2 additional-customer-title">{{ $editee->first_name }} {{ $editee->last_name }}</h4>
                                      <h6 class="card-subtitle">{{ $editee->email_address }}</h6>
                                  </center>
                              </div>
                          </div>
                      @endforeach
                  </div>
                </div>
              </div>
            </div>
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
                                    class="form-control ps-0 form-control-line" autocomplete="honorific-prefix">
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="col-md-12 mb-0">First Name</label>
                            <div class="col-md-12">
                                <input type="text" name="first_name" id="first_name-input" value="{{ $customer->first_name ?? '' }}"
                                    class="form-control ps-0 form-control-line" autocomplete="given-name">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Middle Names</label>
                            <div class="col-md-12">
                                <input type="text" name="middle_names" id="middle_names-input" value="{{ $customer->middle_names ?? '' }}"
                                    class="form-control ps-0 form-control-line" autocomplete="additional-name">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Last Name</label>
                            <div class="col-md-12">
                                <input type="text" name="last_name" id="last_name-input" value="{{ $customer->last_name ?? '' }}"
                                    class="form-control ps-0 form-control-line" autocomplete="family-name">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Date of Birth</label>
                            <div class="col-md-12">
                                <input type="date" name="date_of_birth" id="date_of_birth-input" value="{{ $customer->date_of_birth?->format('Y-m-d') ?? '' }}"
                                    class="form-control ps-0 form-control-line" autocomplete="bday">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Mobile Number</label>
                            <div class="col-md-12">
                                <input type="text" name="mobile_number" id="mobile_number-input" value="{{ $customer->mobile_number ?? '' }}"
                                       class="form-control ps-0 form-control-line" autocomplete="tel">
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
                                    class="form-control ps-0 form-control-line" autocomplete="address-line1">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Address Line 1</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_address_line_1" id="billing_address_line_1-input" value="{{ $customer->billingAddress->address_line_1 ?? '' }}"
                                       class="form-control ps-0 form-control-line" autocomplete="address-line1">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Address Line 2</label>
                            <div class="col-md-12">
                                <input type="text" name="home_address_line_2" id="home_address_line_2-input" value="{{ $customer->homeAddress->address_line_2 ?? '' }}"
                                    class="form-control ps-0 form-control-line" autocomplete="address-line2">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Address Line 2</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_address_line_2" id="billing_address_line_2-input" value="{{ $customer->billingAddress->address_line_2 ?? '' }}"
                                       class="form-control ps-0 form-control-line" autocomplete="address-line2">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Town</label>
                            <div class="col-md-12">
                                <input type="text" name="home_town" id="home_town-input" value="{{ $customer->homeAddress->town ?? '' }}"
                                    class="form-control ps-0 form-control-line" autocomplete="address-level2">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Town</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_town" id="billing_town-input" value="{{ $customer->billingAddress->town ?? '' }}"
                                       class="form-control ps-0 form-control-line" autocomplete="address-level2">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Region</label>
                            <div class="col-md-12">
                                <input type="text" name="home_region" id="home_region-input" value="{{ $customer->homeAddress->region ?? '' }}"
                                    class="form-control ps-0 form-control-line" autocomplete="address-level1">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Region</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_region" id="billing_region-input" value="{{ $customer->billingAddress->region ?? '' }}"
                                       class="form-control ps-0 form-control-line" autocomplete="address-level1">
                            </div>
                        </div>
                        @include('partials.fields.selector.default', ['name' => 'Country', 'field' => 'home_country_id', 'value' => $customer->homeAddress->country_id ?? null, 'width' => 6, 'route' => 'countries',])
                        @include('partials.fields.selector.default', ['name' => 'Country', 'field' => 'billing_country_id', 'value' => $customer->billingAddress->country_id ?? null, 'width' => 6, 'route' => 'countries',])
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Postcode</label>
                            <div class="col-md-12">
                                <input type="text" name="home_postcode" id="home_postcode-input" value="{{ $customer->homeAddress->postcode ?? '' }}"
                                    class="form-control ps-0 form-control-line" autocomplete="postcode">
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label class="col-md-12 mb-0">Postcode</label>
                            <div class="col-md-12">
                                <input type="text" name="billing_postcode" id="billing_postcode-input" value="{{ $customer->billingAddress->postcode ?? '' }}"
                                       class="form-control ps-0 form-control-line" autocomplete="postcode">
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
                                    class="form-control ps-0 form-control-line" autocomplete="given-name">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Middle Name</label>
                            <div class="col-md-12">
                                <input type="text" name="passport_middle_name" id="passport_middle_name-input" value="{{ $customer->passport_middle_name ?? '' }}"
                                    class="form-control ps-0 form-control-line" autocomplete="additional-name">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Last Name</label>
                            <div class="col-md-12">
                                <input type="text" name="passport_last_name" id="passport_last_name-input" value="{{ $customer->passport_last_name ?? '' }}"
                                    class="form-control ps-0 form-control-line" autocomplete="family-name">
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-md-12 mb-0">Gender</label>
                            <div class="col-md-12">
                                <input type="text" name="gender" id="gender-input" value="{{ $customer->gender ?? '' }}"
                                       class="form-control ps-0 form-control-line" autocomplete="sex">
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-md-12 mb-0">Number</label>
                            <div class="col-md-12">
                                <input type="text" name="passport_number" id="passport_number-input" value="{{ $customer->passport_number ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Country of Issue</label>
                            <div class="col-md-12">
                                <input type="text" name="passport_country" id="passport_country-input" value="{{ $customer->passport_country_of_issue ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-md-12 mb-0">Issue Date</label>
                            <div class="col-md-12">
                                <input type="date" name="passport_issue_date" id="passport_issue_date-input" value="{{ $customer?->passport_issue_date?->format('Y-m-d') ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="col-md-12 mb-0">Expiry Date</label>
                            <div class="col-md-12">
                                <input type="date" name="passport_expiry_date" id="passport_expiry_date-input" value="{{ $customer?->passport_expiry_date?->format('Y-m-d') ?? '' }}"
                                    class="form-control ps-0 form-control-line">
                            </div>
                        </div>
                        <hr class="splitter">
                        <div class="form-group">
                            <h4 class="col-md-12 mb-0">Merchandise Clothing Sizes</h4>
                        </div>
                        <hr class="splitter">
                        @include('partials.fields.selector.default',
                            ['name' => 'T-Shirt Size', 'field' => 't_shirt_size_id', 'value' => $customer->t_shirt_size_id ?? 0, 'route' => 't-shirt-size', 'width' => 6])
                        @include('partials.fields.selector.default',
                            ['name' => 'Hat Size', 'field' => 'hat_size_id', 'value' => $customer->hat_size_id ?? 0, 'route' => 'hat-size', 'width' => 6])
                        <hr class="splitter">
                        @include('partials.fields.textarea', ['name' => 'Dietary Requirements', 'field' => 'dietary_notes', 'value' => $customer->dietary_notes, 'rows' => 2])
                        @include('partials.fields.textarea', ['name' => 'Mobility Requirements', 'field' => 'mobility_notes', 'value' => $customer->mobility_notes, 'rows' => 2])
                        @include('partials.fields.textarea', ['name' => 'Other Notes', 'field' => 'other_notes', 'value' => $customer->external_notes, 'rows' => 2])
                        <hr class="splitter">
                        @if(!isset($other))
                        <div class="form-group">
                            <h4 class="col-md-12 mb-0">Change your password</h4>
                        </div>
                        <hr class="splitter">
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Current Password</label>
                            <div class="col-md-12">
                                <input type="password" name="current_password" id="current_password-input" class="form-control ps-0 form-control-line" autocomplete="current-password">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">New Password</label>
                            <div class="col-md-12">
                                <input type="password" name="new_password" id="new_password-input" class="form-control ps-0 form-control-line" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="col-md-12 mb-0">Confirm your new password</label>
                            <div class="col-md-12">
                                <input type="password" name="new_password_confirmation" id="new_password_confirmation-input" class="form-control ps-0 form-control-line" autocomplete="new-password">
                            </div>
                        </div>
                        <hr class="splitter">
                        @endif
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
