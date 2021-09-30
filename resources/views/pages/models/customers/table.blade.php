@extends('layout.main')

@section('title', 'Update Customers')

@section('content')
<a class="btn btn-primary" href="{{ route('customers.create') }}">Create New</a><table id="customer" style="width: 100%;" class="table table-striped">
  <thead class="thead-dark">
  <tr>
    <th scope="col">Title</th>
    <th scope="col">First Name</th>
    <th scope="col">Middle Names</th>
    <th scope="col">Last Name</th>
    <th scope="col">Date Of Birth</th>
    <th scope="col">Mobile Number</th>
    <th scope="col">Other Phone Number</th>
    <th scope="col">Email Address</th>
    <th scope="col">Password</th>
    <th scope="col">Gender</th>
    <th scope="col">Emergency Contact Name</th>
    <th scope="col">Emergency Contact Relationship</th>
    <th scope="col">Emergency Contact Telephone</th>
    <th scope="col">Passport First Name</th>
    <th scope="col">Passport Middle Name</th>
    <th scope="col">Passport Last Name</th>
    <th scope="col">Passport Number</th>
    <th scope="col">Passport Issue Date</th>
    <th scope="col">Passport Expiry Date</th>
    <th scope="col">T Shirt Size Id</th>
    <th scope="col">Hat Size Id</th>
    <th scope="col">Notes</th>
    <th scope="col">Loyalty Number</th>
    <th scope="col">Login Token</th>
    <th scope="col">Home Address Id</th>
    <th scope="col">Billing Address Id</th>
    <th scope="col">Actions</th>
  </tr>
  </thead>
  @foreach($customers as $customer)
    @include('partials.models.customers.row', [
      'customer' => $customer,
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
  @endforeach
</table>
@endsection
