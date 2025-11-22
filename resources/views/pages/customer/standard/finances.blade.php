@extends('layout.customer-standard')

@section('title', 'Balance & Payment')

@php
    $gateways = \Gateway::getDefaultGateway() !== null;
@endphp

@section('content')
    <div class="row payment-balance">
        <div class="col-12">
            <form class="form-horizontal mx-2">
                <div class="form-group finances-select-wrapper">
                    <p class="mb-0  heading">Select Order</p>
                    <select class="form-select order-select" onchange="onOrderChange();" id="booking_reference">
                        @foreach($orders as $selector)
                            <option value='{{ $selector->booking_reference }}'>{{ $selector->tour->name }}
                                ({{ $selector->booking_reference }})
                                @if($selector->cancelled)
                                    (Cancelled)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <a href="#" target="_blank" class="invoice btn btn-primary">View Invoice</a>
                </div>
            </form>
        </div>
        @foreach($orders as $selector)
            @include('partials.customer.finances.block', ['order' => $selector,])
        @endforeach
        @if($gateways)
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <p class="heading">Make Payment</p>
                            <div class="col-md-12">
                                <form class="form-material" action="{{ route('customer.payment.make') }}" method="post">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="booking_reference" id="form-booking-reference">
                                    <div class="row">
                                        <x-customer.input name="amount" :width="10">
                                            Enter Amount
                                        </x-customer.input>
                                        <div class="col-12 col-xl-2 d-flex justify-content-center align-items-center">
                                            <button type="submit" class="btn btn-primary">Make Payment</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <p class="heading">This operator has not enabled online payments</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

@section('footer-script')
    <script>
        let route = "{{ route('customer.invoice', ['reference' => 'reference']) }}"

        function onOrderChange() {
            let newBooking = $('.order-select').val()
            $('.order').hide();
            $('.order-' + newBooking).show();
            $('#form-booking-reference').val(newBooking);
            $('.invoice').prop('href', route.replace('reference', newBooking));
        }

        onOrderChange();
    </script>
@endsection
