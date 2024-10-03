@extends('layout.master')

@section('title', 'View Customer')

@section('content')
    <div class="otm-callout">
        <div class="row">
            <div class="col-xl-3">
                <img src="{{ $customer->avatar_url }}" class="img-thumbnail">
            </div>
            <div class="col-xl-9">
                <div class="row">
                    <div class="col-12">
                        <p>Personal Details</p>
                        <h6 class="fw-bold">{{ $customer->title }} {{ $customer->first_name }} {{ $customer->middle_names }} {{ $customer->last_name }} ({{ $customer->gender }})</h6>
                    </div>
                    <div class="col-12">
                        <p>Email Address</p>
                        <h6 class="fw-bold">
                            @if(isset($customer->email_address))
                            <a href="mailto:{{ $customer->email_address }}">{{ $customer->email_address }}</a>
                                @if($customer->registered)
                                    (Registered)
                                @endif
                            @else
                            Email Address Not Set
                            @endif
                        </h6>
                    </div>
                    <div class="col-12">
                        <p>Phone Number</p>
                        <h6 class="fw-bold"> 
                            <a href="tel:{{ $customer->mobile_number }}">{{ $customer->mobile_number }}</a>
                            @if(isset($customer->other_phone_number))
                                (<a href="tel:{{ $customer->other_phone_number }}">{{ $customer->other_phone_number }}</a>)
                            @endif
                        </h6>
                    </div>
                    <div class="col-12">
                        @if($customer->home_address_id != $customer->billing_address_id)
                            <p>Home Address</p>
                            <h6 class="fw-bold">{{ $customer->homeAddress }}</h6>
                            <p>Billing Address</p>
                            <h6 class="fw-bold">{{ $customer->billingAddress }}</h6>                    
                        @else
                            <p>Address</p>
                            <h6 class="fw-bold">{{ $customer->homeAddress }}</h6>                    
                        @endif                
                    </div>
                    <div class="col-12">
                        <p>Passport Details</p>
                        <h6 class="fw-bold">{{ $customer->passport_first_name }} {{ $customer->passport_middle_names }} {{ $customer->passport_last_name }}, {{ $customer->passport_number }}, Expires {{ f_date($customer->passport_expiry_date) }}</h6>
                    </div>
                    <div class="col-12">
                        <p>Emergency Contact</p>
                        <h6 class="fw-bold">{{ $customer->emergency_contact_name }} ({{ $customer->emergency_contact_relationship }}),  <a href="tel:{{ $customer->emergency_contact_telephone }}">{{ $customer->emergency_contact_telephone }}</a></h6>
                    </div>
                    <div class="col-6">
                        <p>Dietary Requirements</p>
                        <h6 class="fw-bold">{{ $customer->dietary_notes }}</h6>
                    </div>
                    <div class="col-6">
                        <p>Mobility Requirements</p>
                        <h6 class="fw-bold">{{ $customer->mobility_notes }}</h6>
                    </div>
                    <div class="col-6">
                        <p>Internal Notes</p>
                        <h6 class="fw-bold">{{ $customer->internal_notes }}</h6>
                    </div>
                    <div class="col-6">
                        <p>External Notes</p>
                        <h6 class="fw-bold">{{ $customer->external_notes }}</h6>
                    </div>
                    <div class="col-12">
                        <a href="{{ route('customers.edit', ['customer' => $customer,]) }}" class="btn btn-success">
                            {{ Icon::edit() }}
                            Edit Customer
                        </a>
                        <form class="d-none login-as" method="post" action="{{ route('customers.login-as') }}">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{$customer->id}}">
                        </form>
                        <a href="javascript:$('.login-as').submit()" class="btn btn-warning">
                            {{ Icon::customer() }}
                            Login as Customer
                        </a>
                        <form class="d-none forget" method="post" action="{{ route('customers.forget', ['customer' => $customer->id]) }}">
                            @csrf
                        </form>
                        <a href="javascript:confirm('This will permanently wipe this customers personal details from the system, and cannot be reversed, continue?') && $('.forget').submit()" class="btn btn-danger">
                            {{ Icon::forget() }}
                            Forget Customer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>        
    <hr class="splitter"/>
    <x-admin.section.card>
        <table class="datatable table table-striped order-table">
            <thead>
            <tr>
                <th scope="col">Booking Reference</th>
                <th scope="col">Tour Name</th>
                <th scope="col">Ordered On</th>
                <th scope="col">Tour Cost</th>
                <th scope="col">Order Status</th>
            </tr>
            </thead>
            @foreach($customer->orderCustomers as $orderCustomer)
                <tr>
                    <th scope="row"><a href="{{ route('orders.view', ['order' => $orderCustomer->order,]) }}">{{ $orderCustomer->order->booking_reference }}</a></th>
                    <td>{{ $orderCustomer->order->tour->name }}</td>
                    <td>{{ f_datetime($orderCustomer->order->ordered_on) }}</td>
                    <td>{{ f_currency($orderCustomer->tour_cost) }}</td>
                    <td><h6 class="badge badge-{{ $orderCustomer->order->status->color() }} fw-bold">{{ $orderCustomer->order->status->description() }}</h6></td>
                </tr>
            @endforeach
        </table>
    </x-admin.section.card>
    <x-admin.section.card>
        <table class="datatable table table-striped order-table">
            <thead>
            <tr>
                <th scope="col">Quote Reference</th>
                <th scope="col">Name</th>
                <th scope="col">Expiry Date</th>
                <th scope="col">Quote Status</th>
            </tr>
            </thead>
            @foreach($customer->quoteProspects as $prospect)
                @continue($prospect->quote === null)
                <tr>
                    <th scope="row"><a href="{{ route('quotes.view', ['quote' => $prospect->quote,]) }}">{{ $prospect->quote->ref }}</a></th>
                    <td>{{ $prospect->quote->name }}</td>
                    <td>{{ f_date($prospect->quote->expires) }}</td>
                    <td>{{ $prospect->quote->status->badge() }}</td>
                </tr>
            @endforeach
        </table>
    </x-admin.section.card>
@endsection
