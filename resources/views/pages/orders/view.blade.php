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
            <h6 class="fw-bold">{{ $order->tour->name }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Tour Date</p>
            <h6 class="fw-bold">{{ StringFormatter::formatDate($order->tour->date_from) . " to " . StringFormatter::formatDate($order->tour->date_to) }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Order Status</p>
            <h6 class="badge badge-{{ $orderStatus['color'] }} fw-bold">{{ $orderStatus['status'] }}</h6>
        </div>                
        <div class="col-12 col-xl-6">
            <p>Order Value</p>
            <h6 class="fw-bold">{{ StringFormatter::formatCurrency($totalOrderValue) }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Balance Paid</p>
            <h6 class="fw-bold">{{ StringFormatter::formatCurrency($totalPaid) }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Balance Outstanding</p>
            <h6 class="fw-bold">{{ StringFormatter::formatCurrency($totalOrderValue - $totalPaid) }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Next Payment Due</p>
            <h6 class="fw-bold">{{ isset($nextPayment['installment']) ? StringFormatter::formatDate($nextPayment['due']) . ' - ' . StringFormatter::formatCurrency($nextPayment['amount']) : 'All installments paid' }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Internal Notes</p>
            <h6 class="fw-bold">{!! nl2br($order->internal_notes) !!}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>External Notes</p>
            <h6 class="fw-bold">{!! nl2br($order->external_notes) !!}</h6>
        </div>
        <div class="col-12">
            <a href="{{ route('orders.edit', ['order' => $order,]) }}" class="btn btn-success">
                <i class="icon-note"></i>
                Edit Order
            </a>
            <a href="{{ route('tours.view', ['tour' => $order->tour,]) }}" class="btn btn-warning">
                <i class="icon-globe"></i>
                View Tour
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
        @can('create', \App\Models\OrderCustomer::class)
        <div class="py-2 mb-3 text-end">            
            <a href="{{ route('order-customers.create', ['order' => $order, ]) }}" class="btn btn-primary text-white">
                <i class="icon-plus"></i>
                <span>Add Customer</span>
            </a>
        </div>
        @endcan
        <div class="row">
            @foreach($customers as $ordersCustomer)
            <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6">
                <div class="otm-card">
                    <p>{{ ($order->lead_booker_id == $ordersCustomer->id) ? 'Lead Booker' : ' Additional Customer'}}</p>
                    <h6 class="fw-bold">
                        @can('read', \App\Models\OrderCustomer::class)
                        <a href="{{ route('order-customers.view', ['order' => $order, 'orderCustomer' => $ordersCustomer, ]) }}" class="link-info">
                            {{ $ordersCustomer->customer->first_name . " " . $ordersCustomer->customer->last_name }}
                        </a>
                        @else
                            {{ $ordersCustomer->customer->first_name . " " . $ordersCustomer->customer->last_name }}
                        @endcan
                    </h6>
                    <p>Born</p>
                    <h6 class="fw-bold">{{ StringFormatter::formatDate($ordersCustomer->customer->date_of_birth) }}</h6>
                    <p>Passport Number</p>
                    <h6 class="fw-bold">{{ $ordersCustomer->customer->passport_number ?? 'Not Set' }}</h6>
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
                        @can('create', \App\Models\Payment::class)
                        <a href="{{ route('payments.create', ['order' => $order, ]) }}" class="btn btn-success text-white mb-1">
                            <i class="icon-plus"></i>
                            New Payment
                        </a>
                        @endcan
                        <a href="{{ route('orders.invoice.latest', ['order' => $order,]) }}" class="btn btn-primary text-white mb-1">View Invoice</a>
                        <button class="btn btn-primary text-white mb-1" onclick="alert('This is non-functional')">Email Invoice</button>
                        <button class="btn btn-primary text-white mb-1" onclick="alert('This is non-functional')">View Previous Invoices</button>                    
                    </div>
                    <div class="pt-1">
                        <table class="table table-striped" id="payment-table">
                            <thead>
                                <tr>
                                    <th scope="col">Type</th>
                                    <th scope="col">Method</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Paid</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            @foreach($payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_type }}</td>
                                    <td>{{ $payment->paymentMethod->name }}</td>
                                    <td>{{ StringFormatter::formatCurrency($payment->amount) }}</td>
                                    <td>{{ StringFormatter::formatDateTime($payment->paid_on) }}</td>
                                    <td class="actions">
                                        @can('update', \App\Models\Payment::class)
                                        <a href="{{ route('payments.edit', ['order' => $order, 'payment' => $payment,]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-note"></i>
                                            </span>
                                        @endcan
                                        @can('delete', \App\Models\Payment::class)
                                            <a href="#" onclick="$('#payment-{{$payment->id}}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                            <form action="{{ route('payments.delete', ['order' => $order, 'payment' => $payment,]) }}" method="post" id="payment-{{$payment->id}}-delete">
                                                @csrf
                                            </form>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-trash"></i>
                                            </span>
                                        @endcan
                                    </td>
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
                                <th scope="col">Type</th>
                                <th scope="col">Value</th>
                            </tr>
                            </thead>
                            @foreach($customers as $ordersCustomer)
                            <tr>
                                <td>Base: {{ $ordersCustomer->customer->first_name . ' ' . $ordersCustomer->customer->last_name }}</td>
                                <td>{{ StringFormatter::formatCurrency($order->tour->base_price_per_person) }}</td>
                            </tr>
                            @endforeach
                            @foreach($addons as $addon)
                                <tr>
                                    <td>Add-on: {{ $addon['customer']->customer->first_name . ' ' . $addon['customer']->customer->last_name }}</td>
                                    <td>{{ StringFormatter::formatCurrency($addon['addon']->tour_sales_price) }}</td>
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
                    @can('create', \App\Models\ManualAdjustment::class)
                    <div class="pb-3 text-end">
                        <a href="{{ route('manual-adjustments.create', ['order' => $order, ]) }}" class="btn btn-success text-white">
                            <i class="icon-plus"></i>
                            Add Adjustment
                        </a>
                    </div>
                    @endcan
                    <div class="pt-2">
                        <table class="table table-striped" id="order-adjustment-table">
                            <thead>
                            <tr>
                                <th scope="col">Amount</th>
                                <th scope="col">Reason</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($order->adjustments as $adjustment)
                                <tr>
                                    <td>{{ StringFormatter::formatCurrency($adjustment->amount) }}</td>
                                    <td>{{ $adjustment->reason }}</td>
                                    <td class="actions">
                                        @can('update', \App\Models\ManualAdjustment::class)
                                            <a href="{{ route('manual-adjustments.edit', ['order' => $order, 'manualAdjustment' => $adjustment,]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-note"></i>
                                            </span>
                                        @endcan
                                        @can('delete', \App\Models\ManualAdjustment::class)
                                            <a href="#" onclick="$('#madjustment-{{$adjustment->id}}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                            <form action="{{ route('manual-adjustments.delete', ['order' => $order, 'manualAdjustment' => $adjustment,]) }}" method="post" id="madjustment-{{$adjustment->id}}-delete">
                                                @csrf
                                            </form>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-trash"></i>
                                            </span>
                                        @endcan
                                    </td>
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
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            @foreach($customers as $ordersCustomer)
                                @foreach($ordersCustomer->adjustments as $adjustment)
                                <tr>
                                    <td>{{ $ordersCustomer->customer->first_name .  " " . $ordersCustomer->customer->last_name }}</td>
                                    <td>{{ StringFormatter::formatCurrency($adjustment->amount) }}</td>
                                    <td>{{ $adjustment->reason }}</td>
                                    <td class="actions">
                                        @can('update', \App\Models\OrderCustomerAdjustment::class)
                                        <a href="{{ route('order-customer-adjustments.edit', ['order' => $order, 'orderCustomer' => $ordersCustomer, 'orderCustomerAdjustment' => $adjustment,]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-note"></i>
                                            </span>
                                        @endcan
                                        @can('delete', \App\Models\OrderCustomerAdjustment::class)
                                        <a href="#" onclick="$('#oadjustment-{{$adjustment->id}}-delete').submit()" class="btn btn-outline-danger btn-sm mb-1"><i class="icon-trash"></i></a>
                                        <form action="{{ route('order-customer-adjustments.delete', ['order' => $order, 'orderCustomer' => $ordersCustomer, 'orderCustomerAdjustment' => $adjustment,]) }}" method="post" id="oadjustment-{{$adjustment->id}}-delete">
                                            @csrf
                                        </form>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-trash"></i>
                                            </span>
                                        @endcan
                                    </td>
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
