@extends('layout.customer-standard')

@php
$currentUser = \App\Repository\Authentication\CustomerAuthenticationRepository::getCustomer();
$self = $currentUser->id === $customer->id;
$passport = $customer->repository->isPassportLocked();
@endphp

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
                                Primary Phone
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
                                <span class="fw-bold">Passport details are currently locked due to an upcoming tour. If you need to update your passport details, please contact us.</span>
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
    </div>
@endsection
