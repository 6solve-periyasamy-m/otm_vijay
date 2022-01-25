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
                    <div class="col-md-5">
                        <p class="sub-heading">Bill Information</p>
                        <form class="form-material">
                            <div class="form-group">                                
                                <input class="form-control form-control-line" type="text" placeholder="First Name" />
                            </div>
                            <div class="form-group">                                
                                <input class="form-control form-control-line" type="text" placeholder="Last Name" />
                            </div>
                            <div class="form-group">                                
                                <input class="form-control form-control-line" type="text" placeholder="Address" />
                            </div>
                            <div class="form-group">                                
                                <input class="form-control form-control-line" type="text" placeholder="City" />
                            </div>
                            <div class="form-group">                                
                                <input class="form-control form-control-line" type="text" placeholder="Country" />
                            </div>
                            <div class="form-group">                                
                                <input class="form-control form-control-line" type="text" placeholder="Postcode" />
                            </div>
                        </form>
                    </div>
                    <div class="col-md-7">
                        <p class="sub-heading">Make Payment</p>
                        <form class="form-material">
                            <div class="form-material">
                                <div class="form-group">
                                    <input class="form-control form-control-line" type="text" placeholder="Amount to Pay"/>
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
    }
    onOrderChange();
</script>
@endsection
