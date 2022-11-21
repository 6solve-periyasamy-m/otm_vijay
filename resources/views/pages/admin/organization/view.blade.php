@extends('layout.master')

@section('title', 'View Organization')

@php
    /** @var \App\Models\Customer\Organization $organization */
@endphp

@section('footer-script')
    <script>
        $('.datatable').DataTable({fixedHeader: true});
    </script>
@endsection

@section('content')
    <div class="otm-callout">
        <div class="row">
            <div class="col-xl-12">
                <div class="row">
                    <div class="col-3">
                        <p>Organization Name</p>
                        <h6 class="fw-bold">{{ $organization->name }}</h6>
                    </div>
                    <div class="col-3">
                        <p>Email Address</p>
                        <h6 class="fw-bold">
                            @if(isset($organization->contact_email))
                                <a href="mailto:{{ $organization->contact_email }}">{{ $organization->contact_email }}</a>
                            @else
                                Email Address Not Set
                            @endif
                        </h6>
                    </div>
                    <div class="col-3">
                        <p>Phone Number</p>
                        <h6 class="fw-bold">
                            <a href="tel:{{ $organization->contact_number }}">{{ $organization->contact_number }}</a>
                        </h6>
                    </div>
                    <div class="col-6">
                        <p>Internal Notes</p>
                        <h6 class="fw-bold">{{ $organization->internal_notes }}</h6>
                    </div>
                    <div class="col-6">
                        <p>External Notes</p>
                        <h6 class="fw-bold">{{ $organization->external_notes }}</h6>
                    </div>
                    <div class="col-12">
                        <a href="{{ route('organizations.edit', ['organization' => $organization,]) }}" class="btn btn-success">
                            <i class="icon-note"></i>
                            Edit Organization
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="splitter"/>
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <table class="table datatable table-striped order-table">
                        <thead>
                        <tr>
                            <th scope="col">Name</th>
                            <th scope="col">Contact</th>
                            <th scope="col">Orders</th>
                            <th scope="col">Quotes</th>
                        </tr>
                        </thead>
                        @foreach($organization->customers as $customer)
                            <tr>
                                <th scope="row"><a href="{{ route('customers.view', ['customer' => $customer,]) }}">{{ $customer->full_name }}</a></th>
                                <td>{{ $customer->email_address }} ({{ $customer->mobile_number }})</td>
                                <td>{{ $customer->orders()->count() }}</td>
                                <td>{{ $customer->quoteProspects()->count() }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <table class="table datatable table-striped order-table">
                        <thead>
                        <tr>
                            <th scope="col">Booking Reference</th>
                            <th scope="col">Tour Name</th>
                            <th scope="col">Lead</th>
                            <th scope="col">Ordered On</th>
                            <th scope="col">Tour Cost</th>
                            <th scope="col">Order Status</th>
                        </tr>
                        </thead>
                        @foreach($organization->orders as $order)
                            <tr>
                                <th scope="row"><a href="{{ route('orders.view', ['order' => $order,]) }}">{{ $order->booking_reference }}</a></th>
                                <td>{{ $order->tour->name }}</td>
                                <td>{{ $order->leadBooker->customer_name }}</td>
                                <td>{{ f_datetime($order->ordered_on) }}</td>
                                <td>{{ f_currency($order->total) }}</td>
                                <td><h6 class="badge badge-{{ $order->status->color() }} fw-bold">{{ $order->status->description() }}</h6></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <table class="table datatable table-striped order-table">
                        <thead>
                        <tr>
                            <th scope="col">Quote Reference</th>
                            <th scope="col">Name</th>
                            <th scope="col">Lead</th>
                            <th scope="col">Expiry Date</th>
                            <th scope="col">Quote Status</th>
                        </tr>
                        </thead>
                        @foreach($organization->quotes as $quote)
                            <tr>
                                <th scope="row"><a href="{{ route('quotes.view', ['quote' => $quote,]) }}">{{ $quote->ref }}</a></th>
                                <td>{{ $quote->name }}</td>
                                <td>{{ $quote->leadTraveller->name }}</td>
                                <td>{{ f_date($quote->expires) }}</td>
                                <td>{{ $quote->status->badge() }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
