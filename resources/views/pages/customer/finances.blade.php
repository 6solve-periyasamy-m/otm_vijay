@extends('layout.customer')

@section('title', 'Balance & Payment')

@section('content')
<div class="row payment-balance">
    <div class="col-12">
        <form class="form-horizontal mx-2">
            <div class="form-group d-flex align-items-center">
                <p class="mb-0  heading">Select Order</p>
                <select class="form-select order-select" onchange="onOrderChange();" id="booking_reference">
                    @foreach($orders as $order)
                    <option value='{{ $order->booking_reference }}'>{{ $order->booking_reference }}</option>
                    @endforeach
                </select>                
            </div>
        </form>
    </div>
    @foreach($orders as $order)
        @include('partials.customer.finances.block', ['order' => $order,])
    @endforeach
    <div class="col-12">
        <div class="card">
            <div class="card-body">                
                <div class="row">
                    <p class="heading">Make Payment</p>
                    <div class="col-md-12">
                        <p class="sub-heading">Make Payment</p>
                        <form class="form-material" action="{{ route('customer.payment.make') }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="booking_reference" id="form-booking-reference">
                            <div class="form-material row">
                                <div class="form-group col-12 col-xl-10">
                                    <input class="form-control form-control-line" name="amount" type="text" placeholder="Amount to Pay"/>
                                </div>
                                <div class="form-group col-12 col-xl-2">
                                    <input class="form-control form-control-line" type="submit">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer-script')
<script>
    function onOrderChange() {
        let newBooking = $('.order-select').val()
        $('.order').hide();
        $('.order-' + newBooking).show();
        $('#form-booking-reference').val(newBooking);
    }
    onOrderChange();
</script>
@endsection
