@extends('layout.main')

@section('title', 'Edit Settings')

@section('content')
<form action="{{ route('settings.update') }}" method="post">
    @csrf
    <div class="form-group">
        <label for="company_name-input">Company Name</label>
        <input name="company_name" value="{{ old('company_name') ?? \App\Repository\SettingsRepository::getOrDefault('company.name', '') }}" class="form-control" id="company_name-input">
    </div>
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
    <p></p>
    <div class="form-group">
        <label for="address_line_1-input">Company Address Line 1</label>
        <input name="address_line_1" value="{{ old('address_line_1') ?? \App\Repository\SettingsRepository::getOrDefault('company.address.line_1', '') }}" class="form-control" id="address_line_1-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="address_line_2-input">Company Address Line 2</label>
        <input name="address_line_2" value="{{ old('address_line_2') ?? \App\Repository\SettingsRepository::getOrDefault('company.address.line_2', '') }}" class="form-control" id="address_line_2-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="city-input">Company Address City</label>
        <input name="city" value="{{ old('city') ?? \App\Repository\SettingsRepository::getOrDefault('company.address.city', '') }}" class="form-control" id="city-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="region-input">Company Address Region</label>
        <input name="region" value="{{ old('region') ?? \App\Repository\SettingsRepository::getOrDefault('company.address.region', '') }}" class="form-control" id="region-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="postcode-input">Company Address Postcode</label>
        <input name="postcode" value="{{ old('postcode') ?? \App\Repository\SettingsRepository::getOrDefault('company.address.postcode', '') }}" class="form-control" id="postcode-input">
    </div>
    <p></p>
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
    <div class="form-group">
        <label for="booking_prefix-input">Booking Reference Prefix</label>
        <input name="booking_prefix" value="{{ old('booking_prefix') ?? \App\Repository\SettingsRepository::getOrDefault('booking.prefix', '') }}" class="form-control" id="booking_prefix-input">
    </div>
    <p></p>
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
    <div class="form-group">
        <label for="atol_issuer-input">ATOL Issuer</label>
        <input name="atol_issuer" value="{{ old('atol_issuer') ?? \App\Repository\SettingsRepository::getOrDefault('atol.issuer', '') }}" class="form-control" id="atol_issuer-input">
    </div>
    <p></p>
    <div class="form-group">
        <label for="atol_number-input">ATOL Number</label>
        <input name="atol_number" value="{{ old('atol_number') ?? \App\Repository\SettingsRepository::getOrDefault('atol.number', '') }}" class="form-control" id="atol_number-input">
    </div>
    <p></p>
    <div class="form-group">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>
@endsection
