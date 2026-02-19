@extends('layout.master')

@section('title', 'View Customer')
@section('content')
    <div class="row view-customr-both-sectons view-customer-block" style="display:flex;">
        <div class="otm-view-cust-details-neww">
            <div class="otm-view-cust-top-sec" style="display:flex;">
                <div class="view-cust-name">
                    <p>Name</p>
                    <h6 class="fw-bold">{{ $customer->title }} {{ $customer->first_name }} {{ $customer->middle_names }}
                        {{ $customer->last_name }} ({{ $customer->gender }})</h6>
                </div>
                <div class="view-cust-phn-numb">
                    <p>Phone Number</p>
                    <h6 class="fw-bold">
                        <a href="tel:{{ $customer->mobile_number }}">{{ $customer->mobile_number }}</a>
                        @if (isset($customer->other_phone_number))
                            (<a href="tel:{{ $customer->other_phone_number }}">{{ $customer->other_phone_number }}</a>)
                        @endif
                    </h6>
                </div>
                <div class="view-cust-mail-addr">
                    <p>Organization</p>
                    <h6 class="fw-bold">
                        {{ $customer->organization?->name }}
                    </h6>
                </div>
                <div class="view-cust-organizaton">
                    <p>Brand</p>
                    <h6 class="fw-bold">
                        {{ $customer->organization?->brand }}
                    </h6>
                </div>
            </div>
            <div class="otm-view-cust-top-sec" style="display:flex;">
                <div class="view-cust-name">
                    <p>Email Address</p>
                    <h6 class="fw-bold">
                        @if (isset($customer->email_address))
                            <a href="mailto:{{ $customer->email_address }}">{{ $customer->email_address }}</a>
                            @if ($customer->registered)
                                (Registered)
                            @endif
                        @else
                            Email Address Not Set
                        @endif
                    </h6>
                </div>
                <div class="view-cust-phn-numb">
                    <p>Country</p>
                    <h6 class="fw-bold">
                        {{ $customer->organization?->country }}
                    </h6>
                </div>
                <div class="view-cust-mail-addr">
                    <p>In House Consultant:</p>
                    @if ($customer->consultant?->name)
                        <h6 class="fw-bold">
                            {{ $customer->consultant->name }}
                        </h6>
                    @endif
                </div>
                <div class="view-cust-organizaton">
                    <p>Tag</p>
                    <h6 class="fw-bold">
                        {{ $customer->organization?->name }}
                    </h6>
                </div>
            </div>
            <div class="otm-view-cust-nxt-sec" style="display:flex;">
                <div class="otm-view-cust-nxt-left-secton">
                    <div class="col-6">
                        <p>Internal Notes</p>
                        <h6 class="fw-bold">{{ $customer->internal_notes }}</h6>
                    </div>
                    <div class="col-6">
                        <p>External Notes</p>
                        <h6 class="fw-bold">{{ $customer->external_notes }}</h6>
                    </div>
                </div>
                <div class="otm-view-cust-nxt-rght-secton" style="display:flex;">
                    <div class="nxt-rght-secton-frst-colm">
                        <div class="col-6" style="display:flex;">
                            <p>Dietary Requirements:</p>
                            <h6 class="fw-bold">{{ $customer->dietary_notes }}</h6>
                        </div>
                        <div class="col-6" style="display:flex;">
                            <p>Mobility Requirements:</p>
                            <h6 class="fw-bold">{{ $customer->mobility_notes }}</h6>
                        </div>
                    </div>
                    <div class="nxt-rght-secton-scond-colm">
                        <div class="col-12 d-flex">
                            <p>Emergency Contact:</p>
                            <h6 class="fw-bold">{{ $customer->emergency_contact_name }}
                                ({{ $customer->emergency_contact_relationship }}), <a
                                    href="tel:{{ $customer->emergency_contact_telephone }}">{{ $customer->emergency_contact_telephone }}</a>
                            </h6>
                        </div>
                        @if ($customer->consultant?->name)
                            <div class="col-12" style="display:flex;">
                                <p>Inhouse Consultant:</p>
                                <h6 class="fw-bold">
                                    {{ $customer->consultant->name }}
                                </h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="customer-details-edit-log-forgt">
            <div class="col-12">
                <a href="{{ route('customers.edit', ['customer' => $customer]) }}" class="btn btn-success">
                    {{ Icon::edit() }}
                    Edit Customer
                </a>
                <form class="d-none login-as" method="post" action="{{ route('customers.login-as') }}">
                    @csrf
                    <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                </form>
                <a href="javascript:$('.login-as').submit()" class="btn btn-warning">
                    {{ Icon::customer() }}
                    Login as Customer
                </a>
                <form class="d-none forget" method="post"
                    action="{{ route('customers.forget', ['customer' => $customer->id]) }}">
                    @csrf
                </form>
                <a href="javascript:confirm('This will permanently wipe this customers personal details from the system, and cannot be reversed, continue?') && $('.forget').submit()"
                    class="btn btn-danger">
                    {{ Icon::forget() }}
                    Forget Customer
                </a>
            </div>
        </div>
    </div>
    <hr class="splitter" />
    <div class="row">
        <div class="col-xl-6 col-md-6 col-12">
            <x-admin.section.card>
                <x-slot:title>Orders</x-slot:title>
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
                    @foreach ($customer->orderCustomers as $orderCustomer)
                        <tr>
                            <th scope="row"><a
                                    href="{{ route('orders.view', ['order' => $orderCustomer->order]) }}">{{ $orderCustomer->order->booking_reference }}</a>
                            </th>
                            <td>{{ $orderCustomer->order->tour?->name ?? 'Tour Deleted' }}</td>
                            <td>{{ f_datetime($orderCustomer->order->ordered_on) }}</td>
                            <td>{{ f_currency($orderCustomer->tour_cost) }}</td>
                            <td>
                                <h6 class="badge badge-{{ $orderCustomer->order->status->color() }} fw-bold text-wrap">
                                    {{ $orderCustomer->order->status->description() }}</h6>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </x-admin.section.card>
        </div>
        <div class="col-xl-6 col-md-6 col-12">
            <x-admin.section.card>
                <x-slot:title>Quotes</x-slot:title>
                <table class="datatable table table-striped order-table">
                    <thead>
                        <tr>
                            <th scope="col">Quote Reference</th>
                            <th scope="col">Name</th>
                            <th scope="col">Expiry Date</th>
                            <th scope="col">Quote Status</th>
                        </tr>
                    </thead>
                    @foreach ($customer->quoteProspects as $prospect)
                        @continue($prospect->quote === null)
                        <tr>
                            <th scope="row"><a
                                    href="{{ route('quotes.view', ['quote' => $prospect->quote]) }}">{{ $prospect->quote->ref }}</a>
                            </th>
                            <td>{{ $prospect->quote->name }}</td>
                            <td>{{ f_date($prospect->quote->expires) }}</td>
                            <td>{{ $prospect->quote->status->badge() }}</td>
                        </tr>
                    @endforeach
                </table>
            </x-admin.section.card>
        </div>
        <div class="col-xl-6 col-md-6 col-12">
            <x-admin.section.card>
                <x-slot:title>Loyalty Numbers</x-slot:title>
                <div class="row">
                    <livewire:admin.customer.loyalty-number.table :customer="$customer" />
                </div>
            </x-admin.section.card>
        </div>
        <div class="col-xl-6 col-md-6 col-12">
            <x-admin.section.card>
                <x-slot:title>Merchandises</x-slot:title>
                <div class="row">
                    <livewire:admin.customer.merchandise.table :customer="$customer" />
                </div>
            </x-admin.section.card>
        </div>
    </div>
@endsection
