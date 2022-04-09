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
            <div class="form-group d-flex align-items-center">
                <p class="mb-0  heading">Select Order</p>
                <select class="form-select order-select" onchange="onOrderChange(this);" id="booking_reference">
                    @foreach($orders as $selector)
                        <option value='{{ $selector->booking_reference }}' @if($selector->id == $order->id) selected @endif>{{ $selector->booking_reference }} - {{ $selector->tour->name }}</option>
                    @endforeach
                </select>
                <a href="{{ route('customer.invoice', ['reference' => $order->booking_reference]) }}" target="_blank" class="m-l-20 invoice btn btn-primary">Invoice</a>
                @if ($order->has_atol_certificate)
                    <a href="{{ route('customer.atol', ['reference' => $order->booking_reference]) }}" target="_blank" class="m-l-5 invoice btn btn-secondary">ATOL Certificate</a>
                @endif
            </div>
        </form>
    </div>
    <div class="col-2">
        @foreach($editable as $editableOrderCustomer)
            <div class="card other-profile" onclick="window.location = '{{ route('customer.itinerary', ['reference' => $order->booking_reference, 'customer' => $editableOrderCustomer->customer,]) }}'">
                <div class="card-body profile-card">
                    <center class="mt-4">
                        <h4 class="card-title mt-2">{{ $editableOrderCustomer->customer->first_name }} {{ $editableOrderCustomer->customer->last_name }}</h4>
                        <h6 class="card-subtitle">{{ $editableOrderCustomer->customer?->email_address ?? "No Email Set" }}</h6>
                    </center>
                </div>
            </div>
        @endforeach
    </div>
    <div class="col-10 container-fluid">
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
                                <td>{{ StringFormatter::formatDateTime($timeslot['start']) }}
                                    @if(array_key_exists('end', $timeslot))
                                    to {{ StringFormatter::formatDateTime($timeslot['end']) }}
                                    @endif
                                </td>
                                <td>
                                    {{ $timeslot['activity'] }}
                                </td>
                                <td>{{ $timeslot['description'] }}</td>
                            </tr>
                            @php $previousSlot = $currentSlot @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <hr class="splitter">
                <form action="{{ route('customer.notes.update', ['reference' => $order->booking_reference,]) }}" method="post">
                    @csrf
                    @include('partials.fields.textarea', ['name' => 'Order Notes', 'field' => 'notes', 'value' => $order->external_notes, 'rows' => 5])
                    @include('partials.fields.submit')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
