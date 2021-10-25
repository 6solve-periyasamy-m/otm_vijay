@extends('layout.main')

@section('title', 'View Customer')

@section('content')
    <div class="id-card w-100">
        <div class="d-inline align-top">
            <img src="{{ asset('images/exampleavatar.jpg') }}" style="width: 150px; height: 150px;">
        </div>
        <div class="d-inline-flex align-top" style="font-size: 16px; font-weight: 1000;">
            <table>
                <tr class="border-bottom">
                    <th scope="row">Personal Details:</th>
                    <td>
                        {{ $customer->title }} {{ $customer->first_name }} {{ $customer->middle_names }} {{ $customer->last_name }} ({{ $customer->gender }})
                    </td>
                </tr>
                <tr class="border-bottom">
                    <th scope="row">Contact Information:</th>
                    <td>
                        <a href="mailto:{{ $customer->email_address }}">{{ $customer->email_address }}</a>, {{ $customer->mobile_number }}
                        @if(isset($customer->other_phone_number))
                            ({{ $customer->other_phone_number }})
                        @endif
                    </td>
                </tr>
                <tr class="border-bottom">
                    <th scope="row">Address:</th>
                    <td>
                        {{ $customer->homeAddress }}
                        @if($customer->home_address_id != $customer->billing_address_id)
                            (Billing: {{ $customer->billingAddress }})
                        @endif
                    </td>
                </tr>
                <tr class="border-bottom">
                    <th scope="row">Passport Details:</th>
                    <td>
                        {{ $customer->passport_first_name }} {{ $customer->passport_middle_names }} {{ $customer->passport_last_name }}, {{ $customer->passport_number }}, {{ $customer->passport_issue_date }} to {{ $customer->passport_expiry_date }}
                    </td>
                </tr>
                <tr class="border-bottom">
                    <th scope="row">Emergency Contact:</th>
                    <td>
                        {{ $customer->emergency_contact_name }} ({{ $customer->emergency_contact_relationship }}) {{ $customer->emergency_contact_telephone }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
@endsection
