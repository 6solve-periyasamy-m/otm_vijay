@extends('layout.customer')

@section('title', 'Balance & Payment')

@section('content')
<div class="row payment-balance">
    <div class="col-12">
        <form class="form-horizontal mx-2">
            <div class="form-group d-flex align-items-center">
                <p class="mb-0  heading">Select Order</p>
                <select class="form-select order-select" onchange="onOrderChange();" id="booking_reference">
                    @foreach(array_keys($orders) as $key)
                    <option value='{{ $key }}'>{{ $key}}</option>
                    @endforeach
                </select>                
            </div>
        </form>
    </div>
    <div class="col-12">
        <div class="card">            
            <div class="card-body payment-balance">
                <div class="row">
                    <p class="heading">Payment Balance</p>
                    <div class="col-md-4">
                        <p class="payment-value"><span id="total_order_value">0.0</span></p>
                        <label class="payment-label">Total Order Value</label>
                    </div>
                    <div class="col-md-4">
                        <p class="payment-value"><span id="balance_outstanding">0.0</span></p>
                        <label class="payment-label">Balance Outstanding</label>
                    </div>
                    <div class="col-md-4">                        
                        <p class="payment-value badge" id="order_status">Payment Complete</p>
                        <label class="payment-label">Order Status</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row" id="payment_records">
                    <p class="heading">Payment Schedule</p>
                </div>
            </div>
        </div>
    </div>
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
    const monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
    var json_order = '{{ json_encode($orders) }}';
    json_order = json_order.replace(/&quot;/g, '"');
    const orders = JSON.parse(json_order);
    console.log(orders[0]);

    $(document).ready(function() {
        initializeContent();
    });

    var formatter = new Intl.NumberFormat('en-GB', {
        style: 'currency',
        currency: '{{ \App\Repository\SettingsRepository::getOrDefault('system.currency', 'GBP') }}',

    // These options are needed to round to whole numbers if that's what you want.
    //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
    //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
    });
    
    
    function initializeContent() {
        let keys = Object.keys(orders);
        if (keys.length == 0) {
            return;
        }

        setContent(keys[0]);
    }

    function setContent(order_id) {
        let order = orders[order_id];

        setPaymentBalance(order);
        setPaymentRecords(order);
    }

    function setPaymentBalance(order) {
        let totalOrderValue = order.detail.totalOrderValue;
        let totalPaid = order.detail.totalPaid;
        let order_status_color = order.detail.orderStatus.color;
        let order_status = order.detail.orderStatus.status;


        $('#total_order_value').html(formatter.format(totalOrderValue));
        $('#balance_outstanding').html(formatter.format(totalOrderValue - totalPaid));
        $('#order_status').addClass(order_status_color);
        $('#order_status').html(order_status);
    }

    function setPaymentRecords(order) {
        let payments = order.payments;
        let paymentRecordsHTML = `<p class="heading">Payment Schedule</p>`;
        payments.forEach(function(payment) {
            let date = new Date(payment.paid_on);
            let year = date.getFullYear();
            let month = monthNames[date.getMonth()];
            let day = date.getDate();
            paymentRecordsHTML += `
            <div class="col-md-6">
                <div class="payment-record">
                    <div class="d-flex align-items-center">
                        <div class="date">
                            <div class="month-day">` + month + ` ` + day + `</div>
                            <div class="year">` + year + `</div>
                        </div>
                        <div class="method">
                            <div class="value">` + payment.payment_method.name + `</div>
                            <div class="deposit">` + payment.payment_type + `</div>
                        </div>
                    </div>
                    <div class="value">
                        ` + formatter.format(payment.amount) + `
                    </div>
                </div>
            </div>
            `;
        });

        $('#payment_records').html(paymentRecordsHTML);
    }

    function onOrderChange() {        
        let booking_reference = $('#booking_reference').val();        
        setContent(booking_reference);
    }

    function setFixedValue(value) {
        return value.toFixed(1);
    }
</script>
@endsection
