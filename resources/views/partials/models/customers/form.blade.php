<form action="{{ $action }}" method="post">
  @csrf
  <div id="form-group">
    <label for="title-input">Title</label>
    <input name="title" value="{{ $title ?? "" }}" class="form-control" id="title-input">
  </div><p></p>
  <div id="form-group">
    <label for="first_name-input">First Name</label>
    <input name="first_name" value="{{ $first_name ?? "" }}" class="form-control" id="first_name-input">
  </div><p></p>
  <div id="form-group">
    <label for="middle_names-input">Middle Names</label>
    <input name="middle_names" value="{{ $middle_names ?? "" }}" class="form-control" id="middle_names-input">
  </div><p></p>
  <div id="form-group">
    <label for="last_name-input">Last Name</label>
    <input name="last_name" value="{{ $last_name ?? "" }}" class="form-control" id="last_name-input">
  </div><p></p>
  <div id="form-group">
    <label for="date_of_birth-input">Date Of Birth</label>
    <input name="date_of_birth" type="date" value="{{ $date_of_birth ?? "" }}" class="form-control" id="date_of_birth-input">
  </div><p></p>
  <div id="form-group">
    <label for="mobile_number-input">Mobile Number</label>
    <input name="mobile_number" value="{{ $mobile_number ?? "" }}" class="form-control" id="mobile_number-input">
  </div><p></p>
  <div id="form-group">
    <label for="other_phone_number-input">Other Phone Number</label>
    <input name="other_phone_number" value="{{ $other_phone_number ?? "" }}" class="form-control" id="other_phone_number-input">
  </div><p></p>
  <div id="form-group">
    <label for="email_address-input">Email Address</label>
    <input name="email_address" value="{{ $email_address ?? "" }}" class="form-control" id="email_address-input">
  </div><p></p>
  <div id="form-group">
    <label for="password-input">Password</label>
    <input name="password" type="password" value="{{ $password ?? "" }}" class="form-control" id="password-input">
  </div><p></p>
  <div id="form-group">
    <label for="gender-input">Gender</label>
    <input name="gender" value="{{ $gender ?? "" }}" class="form-control" id="gender-input">
  </div><p></p>
  <div id="form-group">
    <label for="emergency_contact_name-input">Emergency Contact Name</label>
    <input name="emergency_contact_name" value="{{ $emergency_contact_name ?? "" }}" class="form-control" id="emergency_contact_name-input">
  </div><p></p>
  <div id="form-group">
    <label for="emergency_contact_relationship-input">Emergency Contact Relationship</label>
    <input name="emergency_contact_relationship" value="{{ $emergency_contact_relationship ?? "" }}" class="form-control" id="emergency_contact_relationship-input">
  </div><p></p>
  <div id="form-group">
    <label for="emergency_contact_telephone-input">Emergency Contact Telephone</label>
    <input name="emergency_contact_telephone" value="{{ $emergency_contact_telephone ?? "" }}" class="form-control" id="emergency_contact_telephone-input">
  </div><p></p>
  <div id="form-group">
    <label for="passport_first_name-input">Passport First Name</label>
    <input name="passport_first_name" value="{{ $passport_first_name ?? "" }}" class="form-control" id="passport_first_name-input">
  </div><p></p>
  <div id="form-group">
    <label for="passport_middle_name-input">Passport Middle Name</label>
    <input name="passport_middle_name" value="{{ $passport_middle_name ?? "" }}" class="form-control" id="passport_middle_name-input">
  </div><p></p>
  <div id="form-group">
    <label for="passport_last_name-input">Passport Last Name</label>
    <input name="passport_last_name" value="{{ $passport_last_name ?? "" }}" class="form-control" id="passport_last_name-input">
  </div><p></p>
  <div id="form-group">
    <label for="passport_number-input">Passport Number</label>
    <input name="passport_number" value="{{ $passport_number ?? "" }}" class="form-control" id="passport_number-input">
  </div><p></p>
  <div id="form-group">
    <label for="passport_issue_date-input">Passport Issue Date</label>
    <input name="passport_issue_date" value="{{ $passport_issue_date ?? "" }}" class="form-control" id="passport_issue_date-input">
  </div><p></p>
  <div id="form-group">
    <label for="passport_expiry_date-input">Passport Expiry Date</label>
    <input name="passport_expiry_date" value="{{ $passport_expiry_date ?? "" }}" class="form-control" id="passport_expiry_date-input">
  </div><p></p>
  <div id="form-group">
    <label for="t_shirt_size_id-input">T Shirt Size Id</label>
    <input name="t_shirt_size_id" value="{{ $t_shirt_size_id ?? "" }}" class="form-control" id="t_shirt_size_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="hat_size_id-input">Hat Size Id</label>
    <input name="hat_size_id" value="{{ $hat_size_id ?? "" }}" class="form-control" id="hat_size_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="notes-input">Notes</label>
    <input name="notes" value="{{ $notes ?? "" }}" class="form-control" id="notes-input">
  </div><p></p>
  <div id="form-group">
    <label for="loyalty_number-input">Loyalty Number</label>
    <input name="loyalty_number" value="{{ $loyalty_number ?? "" }}" class="form-control" id="loyalty_number-input">
  </div><p></p>
  <div id="form-group">
    <label for="login_token-input">Login Token</label>
    <input name="login_token" value="{{ $login_token ?? "" }}" class="form-control" id="login_token-input">
  </div><p></p>
  <div id="form-group">
    <label for="home_address_id-input">Home Address Id</label>
    <input name="home_address_id" value="{{ $home_address_id ?? "" }}" class="form-control" id="home_address_id-input">
  </div><p></p>
  <div id="form-group">
    <label for="billing_address_id-input">Billing Address Id</label>
    <input name="billing_address_id" value="{{ $billing_address_id ?? "" }}" class="form-control" id="billing_address_id-input">
  </div><p></p>
  <div id="form-group">
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</form>
