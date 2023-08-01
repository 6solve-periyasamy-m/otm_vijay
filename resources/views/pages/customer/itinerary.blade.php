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
                           target="_blank" class=" invoice btn btn-secondary">ATOL Certificate</a>
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
                        <p class="heading">Your Itinerary for {{ $order->tour->name }} ({{ $order->booking_reference }})</p>
                        <div class="col-12">
                            <table class="table">
                                <thead>
                                    <tr class="font-bold font-16">
                                        <td class="w-10">Start Time</td>
                                        <td class="w-20">Item</td>
                                        <td class="w-70">Description</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $day = 1 @endphp
                                    @php $previousSlot = null @endphp
                                    @foreach($itinerary as $timeslot)
                                        @php $currentSlot = $timeslot['start']->copy()->setTime(0, 0, 0) @endphp
                                        @if (!isset($previousSlot))
                                            <tr class="text-center font-bold bg-light-blue pagebreak-inside">
                                                <td colspan="3">Day {{ $day }}: {{ f_date($currentSlot) }}</td>
                                            </tr>
                                        @elseif ($previousSlot->diffInDays($currentSlot) >= 1)
                                            @php $day += $previousSlot->diffInDays($currentSlot) @endphp
                                            <tr class="text-center font-bold bg-light-blue pagebreak-inside">
                                                <td colspan="3">Day {{ $day }}: {{ f_date($currentSlot) }}</td>
                                            </tr>
                                        @endif
                                        <tr class="bg-white">
                                            <td data-content="Start Time">{{ f_datetime($timeslot['start']) }}
                                                @if(array_key_exists('end', $timeslot))
                                                to {{ f_datetime($timeslot['end']) }}
                                                @endif
                                            </td>
                                            <td data-content="Item">
                                                {{ $timeslot['activity'] }}
                                            </td>
                                            <td data-content="Description">{{ $timeslot['description'] }}</td>
                                        </tr>
                                        @php $previousSlot = $currentSlot @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
