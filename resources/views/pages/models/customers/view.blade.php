@extends('layout.main')

@section('title', 'View Customer')

@section('content')
    <div class="id-card w-100" style="border: 1px solid black; border-radius: 15px;">
        <div class="d-inline align-top">
            <img src="{{ asset('images/exampleavatar.jpg') }}" style="width: 225px; height: 225px; border-radius: 15px 0 0 15px">
        </div>
        <div class="d-inline-flex align-top">
            <table>
                <tr class="border-bottom">
                    <th style="padding-right: 2px;" scope="row"><strong>Personal Details:</strong></th>
                    <td>
                        {{ $customer->title }} {{ $customer->first_name }} {{ $customer->middle_names }} {{ $customer->last_name }} ({{ $customer->gender }})
                    </td>
                </tr>
                <tr class="border-bottom">
                    <th style="padding-right: 2px;" scope="row"><strong>Email Address:</strong></th>
                    <td>
                        <a href="mailto:{{ $customer->email_address }}">{{ $customer->email_address }}</a>,
                    </td>
                </tr>
                <tr class="border-bottom">
                    <th style="padding-right: 2px;" scope="row"><strong>Phone Number:</strong></th>
                    <td>
                        <a href="tel:{{ $customer->mobile_number }}">{{ $customer->mobile_number }}</a>
                        @if(isset($customer->other_phone_number))
                            (<a href="tel:{{ $customer->other_phone_number }}">{{ $customer->other_phone_number }}</a>)
                        @endif
                    </td>
                </tr>
                @if($customer->home_address_id != $customer->billing_address_id)
                    <tr class="border-bottom">
                        <th style="padding-right: 2px;" scope="row"><strong>Home Address:</strong></th>
                        <td>
                            <strong>{{ $customer->homeAddress }}</strong>
                        </td>
                    </tr>
                    <tr class="border-bottom">
                        <th style="padding-right: 2px;" scope="row"><strong>Billing Address:</strong></th>
                        <td>{{ $customer->billingAddress }}</td>
                    </tr>
                @else
                    <tr class="border-bottom">
                        <th style="padding-right: 2px;" scope="row"><strong>Address:</strong></th>
                        <td>
                            <strong>{{ $customer->homeAddress }}</strong>
                        </td>
                    </tr>
                    <tr cla
                @endif
                <tr class="border-bottom">
                    <th style="padding-right: 2px;" scope="row"><strong>Passport Details:</strong></th>
                    <td>
                        {{ $customer->passport_first_name }} {{ $customer->passport_middle_names }} {{ $customer->passport_last_name }}, {{ $customer->passport_number }}, {{ $customer->passport_issue_date }} to {{ $customer->passport_expiry_date }}
                    </td>
                </tr>
                <tr class="border-bottom">
                    <th style="padding-right: 2px;" scope="row"><strong>Emergency Contact:</strong></th>
                    <td>
                        {{ $customer->emergency_contact_name }} ({{ $customer->emergency_contact_relationship }}),  <a href="tel:{{ $customer->emergency_contact_telephone }}">{{ $customer->emergency_contact_telephone }}</a>
                    </td>
                </tr>
                @if(isset($customer->notes))
                    <tr class="border-bottom">
                        <th style="padding-right: 2px;" scope="row"><strong>Notes:</strong></th>
                        <td>
                            {{ $customer->notes }}
                        </td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
@endsection
