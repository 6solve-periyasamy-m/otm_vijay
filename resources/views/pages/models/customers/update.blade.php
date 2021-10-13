@extends('layout.main')

@section('title', 'Update Customers')

@section('content')
    @include('partials.models.customers.form', ['action' => route('customers.update', ['customer' => $customer,]),
      'title' => $customer->title,
      'first_name' => $customer->first_name,
      'middle_names' => $customer->middle_names,
      'last_name' => $customer->last_name,
      'date_of_birth' => $customer->date_of_birth,
      'mobile_number' => $customer->mobile_number,
      'other_phone_number' => $customer->other_phone_number,
      'email_address' => $customer->email_address,
      'password' => $customer->password,
      'gender' => $customer->gender,
      'emergency_contact_name' => $customer->emergency_contact_name,
      'emergency_contact_relationship' => $customer->emergency_contact_relationship,
      'emergency_contact_telephone' => $customer->emergency_contact_telephone,
      'passport_first_name' => $customer->passport_first_name,
      'passport_middle_name' => $customer->passport_middle_name,
      'passport_last_name' => $customer->passport_last_name,
      'passport_number' => $customer->passport_number,
      'passport_issue_date' => $customer->passport_issue_date,
      'passport_expiry_date' => $customer->passport_expiry_date,
      't_shirt_size_id' => $customer->t_shirt_size_id,
      'hat_size_id' => $customer->hat_size_id,
      'notes' => $customer->notes,
      'loyalty_number' => $customer->loyalty_number,
      'login_token' => $customer->login_token,
      'home_address_id' => $customer->home_address_id,
      'billing_address_id' => $customer->billing_address_id,
    ])
@endsection
