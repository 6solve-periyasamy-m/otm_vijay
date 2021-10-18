@extends('layout.main')

@section('title', 'View Customer')

@section('content')
    Title: {{ $customer->title }}<br/>
    First Name: {{ $customer->first_name }}<br/>
    Middle Names: {{ $customer->middle_names }}<br/>
    Last Name: {{ $customer->last_name }}<br/>
    Date Of Birth: {{ $customer->date_of_birth }}<br/>
    Mobile Number: {{ $customer->mobile_number }}<br/>
    Other Phone Number: {{ $customer->other_phone_number }}<br/>
    Email Address: {{ $customer->email_address }}<br/>
    Password: {{ $customer->password }}<br/>
    Gender: {{ $customer->gender }}<br/>
    Emergency Contact Name: {{ $customer->emergency_contact_name }}<br/>
    Emergency Contact Relationship: {{ $customer->emergency_contact_relationship }}<br/>
    Emergency Contact Telephone: {{ $customer->emergency_contact_telephone }}<br/>
    Passport First Name: {{ $customer->passport_first_name }}<br/>
    Passport Middle Name: {{ $customer->passport_middle_name }}<br/>
    Passport Last Name: {{ $customer->passport_last_name }}<br/>
    Passport Number: {{ $customer->passport_number }}<br/>
    Passport Issue Date: {{ $customer->passport_issue_date }}<br/>
    Passport Expiry Date: {{ $customer->passport_expiry_date }}<br/>
    T Shirt Size Id: {{ $customer->t_shirt_size_id }}<br/>
    Hat Size Id: {{ $customer->hat_size_id }}<br/>
    Notes: {{ $customer->notes }}<br/>
    Loyalty Number: {{ $customer->loyalty_number }}<br/>
    Login Token: {{ $customer->login_token }}<br/>
    Home Address Id: {{ $customer->home_address_id }}<br/>
    Billing Address Id: {{ $customer->billing_address_id }}<br/>
@endsection
