@extends('layout.customer')

@section('title', 'Balance & Payment')

@section('content')
<div class="row payment-balance">
    <div class="col-12">
        <form class="form-horizontal mx-2">
            <div class="form-group finances-select-wrapper">
                <p class="mb-0  heading">Select Order</p>
                <select class="form-select order-select" onchange="onOrderChange();" id="booking_reference">
                    @foreach($orders as $order)
                        <option value='{{ $order->booking_reference }}' @if($order->id == $order->id) selected @endif>{{ $order->booking_reference }} @if($order->cancelled) (Cancelled) @endif - {{ $order->tour->name }}</option>
                    @endforeach
                </select>
                <a href="#" target="_blank" class="invoice btn btn-primary m-l-20">View Invoice</a>
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
                                    <input class="form-control form-control-line amount-input" name="amount" type="text" placeholder="Enter Amount to Pay Here"/>
                                </div>
                                <div class="form-group col-12 col-xl-2">
                                    <input class="form-control form-control-line" type="submit" value="Make Payment">
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
