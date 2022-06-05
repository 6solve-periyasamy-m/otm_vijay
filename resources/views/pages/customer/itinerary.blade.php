@extends('layout.customer')

@section('title', 'View Itinerary')

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
                <p class="mb-0 heading">Select Order</p>
                <select class="form-select order-select" onchange="onOrderChange(this);" id="booking_reference">
                    @foreach($orders as $selector)
                        <option value='{{ $selector->booking_reference }}' @if($selector->id == $order->id) selected @endif @if($selector->cancelled) disabled @endif>{{ $selector->booking_reference }} @if($selector->cancelled) (Cancelled) @endif - {{ $selector->tour->name }}</option>
                    @endforeach
                </select>
                <a href="{{ route('customer.invoice', ['reference' => $order->booking_reference]) }}" target="_blank" class="m-l-20 invoice btn btn-primary">Invoice</a>
                @if ($order->has_atol_certificate)
                    <a href="{{ route('customer.atol', ['reference' => $order->booking_reference]) }}" target="_blank" class="m-l-5 invoice btn btn-secondary">ATOL Certificate</a>
                @endif
            </div>
        </form>
    </div>
    <div class="container">
        <div class="row">
            @if($editable !== null)
                <div class="col-sm-12 col-md-3">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                    Customers
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <div class="row"> <!-- Add col tag to each card that keeps it small on big screen but col-xs-3 elsewise -->
                                        @foreach($editable as $editableOrderCustomer)
                                            <div class="card other-profile col-md-12 col-xs-2" onclick="window.location = '{{ route('customer.itinerary', ['reference' => $order->booking_reference, 'customer' => $editableOrderCustomer->customer,]) }}'">
                                                <div class="card-body profile-card">
                                                    <center class="mt-4">
                                                        <h4 class="card-title mt-2 additional-customer-title">{{ $editableOrderCustomer->customer->first_name }} {{ $editableOrderCustomer->customer->last_name }}</h4>
                                                        <h6 class="card-subtitle additional-customer-subtitle">{{ $editableOrderCustomer->customer?->email_address ?? "No Email Set" }}</h6>
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
            <div class="col-sm-12 {{ $editable !== null ? 'col-md-9' : 'col-md-12' }}">
                <div class="card">
                    <div class="card-body">
                        <p class="heading">Your Itinerary for {{ $order->tour->name }} ({{ $order->booking_reference }})</p>
                        <div class="col-12">
                            <table class="table">
                                <thead>
                                    <tr class="font-bold font-16">
                                        <td class="w-10">Start Time</td>
                                        <td class="w-20">Activity</td>
                                        <td class="w-70">Description</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $day = 1 @endphp
                                    @php $previousSlot = null @endphp
                                    @foreach($itinerary as $timeslot)
                                        @php $currentSlot = $timeslot['start']->copy()->setTime(0, 0, 0) @endphp
                                        @if (!isset($previousSlot))
                                            <tr class="text-center font-bold bg-light-blue">
                                                <td colspan="3">Day {{ $day }}: {{ StringFormatter::formatDate($currentSlot) }}</td>
                                            </tr>
                                        @elseif ($previousSlot->diffInDays($currentSlot) >= 1)
                                            @php $day += $previousSlot->diffInDays($currentSlot) @endphp
                                            <tr class="text-center font-bold bg-light-blue">
                                                <td colspan="3">Day {{ $day }}: {{ StringFormatter::formatDate($currentSlot) }}</td>
                                            </tr>
                                        @endif
                                        <tr class="bg-white">
                                            <td data-content="Start Time">{{ StringFormatter::formatDateTime($timeslot['start']) }}
                                                @if(array_key_exists('end', $timeslot))
                                                to {{ StringFormatter::formatDateTime($timeslot['end']) }}
                                                @endif
                                            </td>
                                            <td data-content="Activity">
                                                {{ $timeslot['activity'] }}
                                            </td>
                                            <td data-content="Description">{{ $timeslot['description'] }}</td>
                                        </tr>
                                        @php $previousSlot = $currentSlot @endphp
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr class="splitter">
                        <form action="{{ route('customer.notes.update', ['reference' => $order->booking_reference, 'orderCustomer' => $orderCustomer,]) }}" method="post">
                            @csrf
                            @if(\App\Repository\OrderRepository::isLeadBooker($order, \App\Repository\CustomerAuthenticationRepository::getCustomer()))
                            @include('partials.fields.textarea', ['name' => 'Order Notes', 'field' => 'order_notes', 'value' => $order->external_notes, 'rows' => 2])
                            @endif
                            @include('partials.fields.textarea', ['name' => 'Customer Specific Order Notes', 'field' => 'order_customer_notes', 'value' => $orderCustomer->external_notes, 'rows' => 2])
                            @include('partials.fields.textarea', ['name' => 'Accommodation Notes', 'field' => 'accommodation_notes', 'value' => $orderCustomer->accommodation_notes, 'rows' => 2])
                            @include('partials.fields.textarea', ['name' => 'Activity Notes', 'field' => 'activity_notes', 'value' => $orderCustomer->activity_notes, 'rows' => 2])
                            @include('partials.fields.textarea', ['name' => 'Flight Notes', 'field' => 'flight_notes', 'value' => $orderCustomer->flight_notes, 'rows' => 2])
                            @include('partials.fields.textarea', ['name' => 'Transport Notes', 'field' => 'transport_notes', 'value' => $orderCustomer->transport_notes, 'rows' => 2])
                            @include('partials.fields.submit')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
