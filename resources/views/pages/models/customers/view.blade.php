@extends('layout.master')

@section('title', 'View Customer')

@section('footer-script')
    <script>
        $('.order-table').DataTable({fixedHeader: true});
    </script>
@endsection

@section('content')
    <div class="otm-callout">
        <div class="row">
            <div class="col-xl-3">
                <img src="{{ asset($customer->profile_picture) }}" class="img-thumbnail">
            </div>
            <div class="col-xl-9">
                <div class="row">
                    <div class="col-12">
                        <p>Personal Details</p>
                        <h6 class="fw-bold">{{ $customer->title }} {{ $customer->first_name }} {{ $customer->middle_names }} {{ $customer->last_name }} ({{ $customer->gender }})</h6>
                    </div>
                    <div class="col-12">
                        <p>Email Address</p>
                        <h6 class="fw-bold"><a href="mailto:{{ $customer->email_address }}">{{ $customer->email_address }}</a></h6>
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
                        <h6 class="fw-bold">{{ $customer->passport_first_name }} {{ $customer->passport_middle_names }} {{ $customer->passport_last_name }}, {{ $customer->passport_number }}, {{ $customer->passport_issue_date }} to {{ $customer->passport_expiry_date }}</h6>
                    </div>
                    <div class="col-12">
                        <p>Emergency Contact</p>
                        <h6 class="fw-bold">{{ $customer->emergency_contact_name }} ({{ $customer->emergency_contact_relationship }}),  <a href="tel:{{ $customer->emergency_contact_telephone }}">{{ $customer->emergency_contact_telephone }}</a></h6>
                    </div>
                    <div class="col-12">
                        <p>Notes</p>
                        <h6 class="fw-bold">{{ $customer->notes }}</h6>
                    </div>                    
                    <div class="col-12">
                        <a href="{{ route('customers.edit', ['customer' => $customer,]) }}" class="btn btn-success">
                            <i class="icon-note"></i>
                            Edit Customer
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>        
    <hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;"/>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped order-table">
                <thead>
                    <tr>
                        <th scope="col">Booking Reference</th>
                        <th scope="col">Tour Name</th>
                        <th scope="col">Ordered On</th>
                        <th scope="col">Tour Cost</th>
                    </tr>
                </thead>
                @foreach($customer->orderCustomers as $orderCustomer)
                    <tr>
                        <th scope="row"><a href="{{ route('orders.view', ['order' => $orderCustomer->order,]) }}">{{ $orderCustomer->order->booking_reference }}</a></th>
                        <td>{{ $orderCustomer->order->tour->name }}</td>
                        <td>{{ $orderCustomer->order->ordered_on }}</td>
                        <td>{{ $orderCustomer->tour_cost }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
