@extends('layout.customer-standard')

@section('title', 'Payment Successful')

@section('content')
    <x-admin.section.card>
        <hr class="splitter">
        <h4 class="col-md-12 mb-0">Payment Processed Successfully</h4>
        <hr class="splitter">
        Your payment has been successfully processed through our gateway, and should be reflected in your account in the coming days.<br /><br />
        Email confirmation of this payment will be sent to the Lead Booker of your Order. If you wish to make any changes to your booking such as updating your details, making instalment payments,
        or purchasing any available add-ons or upgrades then this can be done via your <a href="{{ route('customer.portal') }}" class="link link-info">dashboard</a>
    </x-admin.section.card>
@endsection
