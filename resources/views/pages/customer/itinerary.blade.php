@extends('layout.customer')

@section('title', 'View Itinerary')

@php
$orderNotesLock = $order->tour->repository->isOrderNotesLocked();
$accommodationLock = $order->tour->repository->isAccommodationLocked();
$activityLock = $order->tour->repository->isActivityLocked();
$flightLock = $order->tour->repository->isFlightLocked();
$transportLock = $order->tour->repository->isTransportLocked();
@endphp

@push('footer-stack')
    <script>
        const route = "{{ route('customer.itinerary') }}"

        function onOrderChange(selector) {
            window.location = route + '/' + $(selector).val();
        }
    </script>
@endpush

@section('content')
    <div class="row payment-balance">
        <div class="col-12">
            <form class="form-horizontal mx-2">
                <div class="form-group order-select-wrapper">
                    <p class="mb-0  heading">Select Order</p>
                    <select class="form-select order-select" onchange="onOrderChange(this);" id="booking_reference">
                        @foreach($orders as $selector)
                            <option value='{{ $selector->booking_reference }}' @if($selector->id == $order->id) selected
                                    @endif @if($selector->cancelled) disabled @endif>{{ $selector->tour->name }} ({{ $selector->booking_reference }} @if($selector->cancelled)
                                    (Cancelled)
                                @endif &#41;</option>
                        @endforeach
                    </select>
                    <a href="{{ route('customer.invoice', ['reference' => $order->booking_reference]) }}"
                       target="_blank" class=" invoice btn btn-primary">Invoice</a>
                    @if ($order->has_atol)
                        <a href="{{ route('customer.atol', ['reference' => $order->booking_reference]) }}"
                           target="_blank" class=" atol btn btn-secondary">ATOL Certificate</a>
                    @endif
                </div>
            </form>
        </div>
        <div class="container">
        <div class="row">
            @if(sizeof($editable ?? []) > 1)
                <div class="col-sm-12 col-md-3">
                    <div class="card other-profile col-md-12 col-xs-2" onclick="window.location = '{{ route('customer.itinerary', ['reference' => $order->booking_reference, 'customer' => $orderCustomer->customer,]) }}'">
                        <div class="card-body profile-card">
                            <center class="mt-4">
                                <h4 class="card-title mt-2 additional-customer-title">{{ $orderCustomer->customer->first_name }} {{ $orderCustomer->customer->last_name }}</h4>
                                <h6 class="card-subtitle additional-customer-subtitle">{{ $orderCustomer->customer?->email_address ?? "No Email Set" }}</h6>
                            </center>
                        </div>
                    </div>
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Customers
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row">
            @foreach($editable as $editableOrderCustomer)
                @if ($editableOrderCustomer->id === $orderCustomer->id) @continue @endif
                                            <div class="card other-profile col-md-12 col-xs-2"
                     onclick="window.location = '{{ route('customer.itinerary', ['reference' => $order->booking_reference, 'customer' => $editableOrderCustomer->customer,]) }}'">
                    <div class="card-body profile-card">
                        <center class="mt-4">
                            <h4 class="card-title mt-2additional-customer-title">{{ $editableOrderCustomer->customer->first_name }} {{ $editableOrderCustomer->customer->last_name }}</h4>
                            <h6 class="card-subtitleadditional-customer-subtitle">{{ $editableOrderCustomer->customer?->email_address ?? "No Email Set" }}</h6>
                        </center>
                    </div>
                </div>
            @endforeach
        </div>
        </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-sm-12 {{ sizeof($editable ?? []) > 1 ? 'col-md-9' : 'col-md-12' }}">
                <div class="card">
                    <div class="card-body">
                        <p class="heading d-inline">Your Itinerary for {{ $order->tour->name }} ({{ $order->booking_reference }})</p>

                        <a href="{{ route('customer.itinerary.download', ['reference' => $order->booking_reference, 'customer' => $orderCustomer->customer_id]) }}"
                           target="_blank" class="float-end invoice btn btn-primary">Download Itinerary</a>
                    </div>
                </div>
                @foreach($orderCustomer->repository->getComponentsForItinerary() as $day => $components)
                    <div class="mx-2">
                        <x-customer.accordion id="day-{{$day}}" nobg nocontainer>
                            <x-slot:header class="card-body">
                                <h2 class="mb-0" style="width: 100%; text-align: center;">{{ \Carbon\Carbon::createFromTimestamp($day)->format('l jS F Y') }}</h2>
                            </x-slot:header>
                            @foreach($components as $component)
                                @include('partials.customer.itinerary', ['orderComponent' => $component,])
                            @endforeach
                        </x-customer.accordion>
                    </div>
                @endforeach
                <form action="{{ route('customer.notes.update', ['reference' => $order->booking_reference, 'orderCustomer' => $orderCustomer,]) }}" method="post" class="form-horizontal form-material">
                    @csrf
                    @php $isLead = $order->repository->isLeadBooker(\App\Repository\Authentication\CustomerAuthenticationRepository::getCustomer()) @endphp
                    <div class="card">
                        <div class="card-body">
                            <h2 class="col-md-12 mb-0">Travel Insurance</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body row">
                            <x-customer.input name="travel_insurer" value="{{ $orderCustomer->travel_insurer }}" width="6">
                                Travel Insurer
                            </x-customer.input>
                            <x-customer.input name="policy_number" value="{{ $orderCustomer->policy_number }}" width="6">
                                Policy Number
                            </x-customer.input>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="col-md-12 mb-0">Notes About Your Tour</h2>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body row">
                            @if($orderNotesLock || $accommodationLock || $activityLock || $flightLock || $transportLock)
                                <span class="fw-bold col-xl-12">
                                    Some or all of the below sections may be locked due to the tour starting soon. Changes made may not be reflected, therefore if any urgent changes are required, please contact us.
                                </span>
                            @endif
                            @if($isLead)
                            <x-customer.input.text-area disabled="{{$orderNotesLock}}" name="order_notes" value="{{ $order->external_notes }}" width="6">
                                Order Notes
                            </x-customer.input.text-area>
                            @endif
                            <x-customer.input.text-area disabled="{{$orderNotesLock}}" name="order_customer_notes" value="{{ $orderCustomer->external_notes }}" width="{{ $isLead ? 6 : 12 }}">
                                Customer Specific Order Notes
                            </x-customer.input.text-area>
                            <x-customer.input.text-area disabled="{{$accommodationLock}}" name="accommodation_notes" value="{{ $orderCustomer->accommodation_notes }}" width="3">
                                Accommodation Notes
                            </x-customer.input.text-area>
                            <x-customer.input.text-area disabled="{{$activityLock}}" name="activity_notes" value="{{ $orderCustomer->activity_notes }}" width="3">
                                Activity Notes
                            </x-customer.input.text-area>
                            <x-customer.input.text-area disabled="{{$flightLock}}" name="flight_notes" value="{{ $orderCustomer->flight_notes }}" width="3">
                                Flight Notes
                            </x-customer.input.text-area>
                            <x-customer.input.text-area disabled="{{$transportLock}}" name="transport_notes" value="{{ $orderCustomer->transport_notes }}" width="3">
                                Transport Notes
                            </x-customer.input.text-area>
                            @include('partials.fields.submit')
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div></div>
</div>
@endsection
