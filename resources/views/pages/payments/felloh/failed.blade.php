@extends('layout.customer-standard')

@section('title', 'Failed to Fetch Gateway')

@section('content')
    <x-admin.section.card>
        <hr class="splitter">
        <h4 class="col-md-12 mb-0">Failed to fetch payment information</h4>
        <hr class="splitter">
        Something went wrong, and we were unable to process your payment request. Please check that you have the following information in <a class="link-primary" href="{{route('customer.edit')}}">Your Details</a>:
        <ul>
            <li>First Name</li>
            <li>Last Name</li>
            <li>Address Line 1</li>
            <li>Postcode</li>
        </ul>

        If you have all this information and the error persists, please contact us at <a class="link-primary" href="mailto:{{setting('company.contact.email')}}">{{setting('company.contact.email')}}</a>.
    </x-admin.section.card>
@endsection
