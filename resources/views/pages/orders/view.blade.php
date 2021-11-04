@extends('layout.master')

@section('title', 'View Order')
{{-- TODO: Tidy up CSS --}}

@section('footer-script')
<script type="text/javascript">
    $(document).ready(function () {
        $('#payment-table').DataTable({fixedHeader: true});
        $('#cost-table').DataTable({fixedHeader: true});
        $('#order-adjustment-table').DataTable({fixedHeader: true});
        $('#customer-adjustment-table').DataTable({fixedHeader: true});
    });
</script>
@endsection
@section('content')
{{-- Header Details --}}
<div class="otm-callout" id="header-details">
    <div class="row">
        <div class="col-12 col-xl-6">
            <p>Booking Reference</p>
            <h6 class="fw-bold">{{ $order->booking_reference }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Tour</p>
            <h6 class="fw-bold">{{ $order->tour->title }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Tour Date</p>
            <h6 class="fw-bold">{{ $order->tour->date_from . " to " . $order->tour->date_to }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Payment Status</p>
            <h6 class="badge {{ $totalPaid >= $totalOrderValue ? 'badge-success' : 'badge-danger' }} fw-bold">{{ $totalPaid >= $totalOrderValue ? "Paid in Full" : "Balance Outstanding" }}</h6>
        </div>
        <div class="col-12">
            <a href="{{ route('orders.edit', ['order' => $order,]) }}" class="btn btn-success">
                <i class="icon-note"></i>
                Edit Order
            </a>
        </div>
    </div>
</div>
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;">

{{-- Order Overview and Customers Section --}}
<div class="heading pt-2 pb-md-3 pb-2">
    <h2 class="fw-bold">Customers</h2>        
</div>
<div class="card" id="section-1">
    <div class="card-body">
        <div class="py-2 mb-3 text-end">            
            <a href="{{ route('order-customers.create', ['order' => $order, ]) }}" class="btn btn-primary text-white">
                <i class="icon-plus"></i>
                <span>Add Customer</span>
            </a>
        </div>        
        <div class="otm-callout" id="overview-details" >
            <div class="row">
                <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6">
                    <p>Order Value</p>
                    <p class="fw-bold">{{ $totalOrderValue }}</p>
                </div>
                <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6">
                    <p>Balance Paid</p>
                    <p class="fw-bold">{{ $totalPaid }}</p>
                </div>
                <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6">
                    <p>Balance Outstanding</p>
                    <p class="fw-bold">{{ $totalOrderValue - $totalPaid }}</p>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($customers as $ordersCustomer)
            <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6">
                <div class="otm-card">
                    <p>Lead Broker</p>
                    <h6 class="fw-bold">
                        <a href="{{ route('order-customers.view', ['order' => $order, 'orderCustomer' => $ordersCustomer, ]) }}" class="link-info">
                            {{ $ordersCustomer->customer->first_name . " " . $ordersCustomer->customer->last_name }}
                        </a>
                    </h6>
                    <p>Born</p>
                    <h6 class="fw-bold">{{ $ordersCustomer->customer->date_of_birth }}</h6>
                    <p>Passport Number</p>
                    <h6 class="fw-bold">{{ $ordersCustomer->customer->passport_number }}</h6>
                </div>
            </div>
            @endforeach
        </div>        
    </div>
</div>
<hr style="border-bottom: 5px solid #cccccc; border-radius: 2px;">

<div class="heading pt-2 pb-md-3 pb-2">
    <h2 class="fw-bold">Billing & Payments</h2>        
</div>

<div id="billing-section">        
    {{-- Payments Table--}}
    <div class="row">
        <div class="col-xl-6" id="payments-section">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Payments</h4>
                    </div>
                    <div class="pb-3 text-end">
                        <a href="{{ route('payments.create', ['order' => $order, ]) }}" class="btn btn-success text-white mb-1">
                            <i class="icon-plus"></i>
                            New Payment
                        </a>
                        <a href="{{ route('orders.invoice.latest', ['order' => $order,]) }}" class="btn btn-primary text-white mb-1">View Invoice</a>
                        <button class="btn btn-primary text-white mb-1" onclick="alert('This is non-functional')">Email Invoice</button>
                        <button class="btn btn-primary text-white mb-1" onclick="alert('This is non-functional')">View Previous Invoices</button>                    
                    </div>
                    <div class="pt-1">
                        <table class="table table-striped" id="payment-table">
                            <thead>
                                <tr>
                                    <th scope="col">Type</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Due Date</th>
                                    <th scope="col">Paid Date</th>
                                </tr>
                            </thead>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->paymentMethod->name }}</td>
                                    <td>{{ $payment->amount }}</td>
                                    <td>{{ $payment->paid_on }}</td> {{-- TODO: Get actual due date --}}
                                    <td>{{ $payment->paid_on }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Costs</h4>
                    </div>
                    <div>
                        <table class="table table-striped" id="cost-table">
                            <thead>
                            <tr>
                                <th scope="col" >Type</th>
                                <th scope="col" >Value</th>
                            </tr>
                            </thead>
                            <tr>
                                <td>Base</td>
                                <td>{{ $order->tour->base_price_per_person * sizeof($customers) }}</td>
                            </tr>
                            @foreach($addons as $addon)
                                <tr>
                                    <td>Add-on</td>
                                    <td>{{ $addon->tour_sales_price }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>        
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Order Adjustments</h4>
                    </div>
                    <div class="pb-3 text-end">
                        <a href="{{ route('manual-adjustments.create', ['order' => $order, ]) }}" class="btn btn-success text-white">
                            <i class="icon-plus"></i>
                            Add Adjustment
                        </a>
                    </div>
                    <div class="pt-2">
                        <table class="table table-striped" id="order-adjustment-table">
                            <thead>
                            <tr>
                                <th scope="col">Amount</th>
                                <th scope="col">Reason</th>
                            </tr>
                            </thead>
                            @foreach($order->adjustments as $adjustment)
                                <tr>
                                    <td>{{ $adjustment->amount }}</td>
                                    <td>{{ $adjustment->reason }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Customer Adjustments</h4>
                    </div>
                    <div>
                        <table class="table table-striped" id="customer-adjustment-table">
                            <thead>
                            <tr>
                                <th scope="col">Customer</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Reason</th>
                            </tr>
                            </thead>
                            @foreach($customers as $ordersCustomer)
                                @foreach($ordersCustomer->adjustments as $adjustment)
                                <tr>
                                    <td>{{ $ordersCustomer->customer->first_name .  " " . $ordersCustomer->customer->last_name }}</td>
                                    <td>{{ $adjustment->amount }}</td>
                                    <td>{{ $adjustment->reason }}</td>
                                </tr>
                                @endforeach
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>        
    </div>    
</div>

{{-- Closing Container--}}
@endsection
