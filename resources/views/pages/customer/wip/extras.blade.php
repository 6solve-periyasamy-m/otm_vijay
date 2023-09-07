@extends('layout.customer')

@section('title', 'View Itinerary')

@push('footer-stack')
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
@endpush

@push('footer-stack')
    <script>
        let totalCost = 0;
        const route = "{{ route('customer.itinerary') }}"
        function onOrderChange(selector) {
            window.location = route + '/' + $(selector).val();
        }
        function checkboxChange(checkbox) {
            if ($(checkbox).is(':checked')) {
                totalCost += parseFloat($(checkbox).attr('cost'));
            } else {
                totalCost -= parseFloat($(checkbox).attr('cost'));
            }
            updateCost();
        }
        function radioChange(radio) {
            radio = $(radio);
            let previous = $('input[type=radio][name=' + radio.attr('name') + '][previous]');
            if (previous !== null) {
                totalCost -= parseFloat(previous.attr('cost'));
            }
            previous.removeAttr('previous')
            radio.attr('previous', '')
            totalCost += parseFloat(radio.attr('cost'))
            updateCost();
        }
        function updateCost() {
            $('.amount-input').textContent = '' + totalCost;
        }
    </script>
@endpush
@section('content')
    <style>
        .extra-box {
            border-bottom: 1px solid #9999dd;
        }
    </style>
<div class="row payment-balance">
    <div class="col-12">
        <form class="form-horizontal mx-2">
            <div class="form-group d-flex align-items-center">
                <p class="mb-0  heading">Select Order</p>
                <select class="form-select order-select" onchange="onOrderChange(this);" id="booking_reference">
                    @foreach($orders as $selector)
                        <option value='{{ $selector->booking_reference }}' @if($order->id == $selector->id) selected @endif>{{ $selector->tour->name }} ({{ $selector->booking_reference }})</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <p class="heading">Your Itinerary for {{ $order->tour->name }}</p>
                <form class="form-material" action="{{ route('customer.payment.make') }}" method="post">
                <div class="col-12">
                    <div class="extra-box">
                        <h1>Accommodation</h1>
                    </div>
                    @foreach($accommodation as $data)
                        @include('partials.customer.extra', ['data' => $data, 'section' => 'accommodation'])
                    @endforeach
                    <hr class="splitter">
                    <div class="extra-box">
                        <h1>Activities</h1>
                    </div>
                    @foreach($activities as $data)
                        @include('partials.customer.extra', ['data' => $data, 'section' => 'activity'])
                    @endforeach
                    <hr class="splitter">
                    <div class="extra-box">
                        <h1>Flights</h1>
                    </div>
                    @foreach($flights as $data)
                        @include('partials.customer.extra', ['data' => $data, 'section' => 'flight'])
                    @endforeach
                    <hr class="splitter">
                    <div class="extra-box">
                        <h1>Transport</h1>
                    </div>
                    @foreach($transports as $data)
                        @include('partials.customer.extra', ['data' => $data, 'section' => 'transport'])
                    @endforeach
                    <hr class="splitter">
                    {{ csrf_field() }}
                    <input type="hidden" name="booking_reference" id="form-booking-reference" value="{{ $order->booking_reference }}">
                    <div class="form-material row">
                        <div class="form-group col-12 col-xl-2">
                            <span class="form-control form-control-line amount-input">
                                Guideline Price
                            </span>
                        </div>
                        <div class="form-group col-12 col-xl-6">
                            <span class="form-control form-control-line amount-input" name="amount" type="text" placeholder="Amount to Pay">

                            </span>
                        </div>
                        <div class="form-group col-12 col-xl-2">
                            <input class="form-control form-control-line" type="submit" value="Make Payment">
                        </div>
                        <div class="form-group col-12 col-xl-2">
                            <input class="form-control form-control-line" type="submit" value="Add to Account">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
