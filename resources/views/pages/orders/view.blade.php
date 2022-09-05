@php
/**
 * @param \App\Models\Order\Order $order;
 */
@endphp

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
        $('#schedule-table').DataTable({fixedHeader: true,});
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
            <h6 class="fw-bold">{{ f_date($order->tour->date_from) . " to " . f_date($order->tour->date_to) }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Order Status</p>
            <h6 class="badge badge-{{ $order->status->color() }} fw-bold">{{ $order->status->description() }}</h6>
        </div>                
        <div class="col-12 col-xl-6">
            <p>Order Value</p>
            <h6 class="fw-bold">
                @if($order->cancelled)
                    {{ f_currency($order->total) }} ({{ f_currency($order->cost) }} before cancellation)
                @else
                    {{ f_currency($order->cost + $order->total_adjustments) }} ({{ f_currency($order->cost) }} before adjustments)
                @endif
            </h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Balance Paid</p>
            <h6 class="fw-bold">{{ f_currency($order->paid) }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Balance Outstanding</p>
            <h6 class="fw-bold">{{ f_currency($order->remaining) }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Next Payment Due</p>
            <h6 class="fw-bold">{{ $order->next_installment !== null ? f_date($order->next_installment->due_on) . ' - ' . f_currency($order->next_installment->amount) : 'All installments paid' }}</h6>
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
            <a href="{{ route('orders.occupancy', ['order' => $order,]) }}" class="btn btn-info">
                <i class="icon-note"></i>
                Edit Room Sharing Data
            </a>
            <a href="{{ route('tours.view', ['tour' => $order->tour,]) }}" class="btn btn-warning">
                <i class="icon-globe"></i>
                View Tour
            </a>
            @if(isset($order->quote))
            <a href="{{ route('quotes.view', ['quote' => $order->quote,]) }}" class="btn btn-warning">
                <i class="icon-wallet"></i>
                View Quote
            </a>
            @endif
            @if($order->has_atol && !$order->cancelled)
            <a href="{{ route('orders.atol', ['order' => $order,]) }}" class="btn btn-secondary">
                <i class="icon-plane"></i>
                ATOL Certificate
            </a>
            @endif
            @can('delete', \App\Models\Order\Order::class)
                @if($order->cancelled)
                    <a href="#" onclick="$('#order-restore').submit()" class="btn btn-warning"><i class="icon-trash"></i>Restore Order</a>
                    <form action="{{ route('orders.restore', ['order' => $order,]) }}" method="post" id="order-restore">
                        @csrf
                    </form>
                @else
                    <a href="#" onclick="$('#order-delete').submit()" class="btn btn-danger"><i class="icon-trash"></i>Cancel Order</a>
                    <form action="{{ route('orders.delete', ['order' => $order,]) }}" method="post" id="order-delete">
                        @csrf
                    </form>
                @endif
            @endcan
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
        @can('create', \App\Models\Order\OrderCustomer::class)
        <div class="py-2 mb-3 text-end">            
            <a href="{{ route('order-customers.create', ['order' => $order, ]) }}" class="btn btn-primary text-white">
                <i class="icon-plus"></i>
                <span>Add Customer</span>
            </a>
        </div>
        @endcan
        <div class="row">
            @foreach($order->orderCustomers as $ordersCustomer)
            <div class="col-xxl-2 col-xl-3 col-md-4 col-sm-6">
                <div class="otm-card">
                    <p>{{ ($order->lead_booker_id == $ordersCustomer->id) ? 'Lead Booker' : ' Additional Customer'}}</p>
                    <h6 class="fw-bold">
                        @can('read', \App\Models\Order\OrderCustomer::class)
                        <a href="{{ route('order-customers.view', ['order' => $order, 'orderCustomer' => $ordersCustomer, ]) }}" class="link-info">
                            {{ $ordersCustomer->customer->first_name . " " . $ordersCustomer->customer->last_name }}
                        </a>
                        @else
                            {{ $ordersCustomer->customer->first_name . " " . $ordersCustomer->customer->last_name }}
                        @endcan
                    </h6>
                    <p>Born</p>
                    <h6 class="fw-bold">{{ f_date($ordersCustomer->customer->date_of_birth) }}</h6>
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
                        @can('create', \App\Models\Order\Payment\Payment::class)
                        <a href="{{ route('payments.create', ['order' => $order, ]) }}" class="btn btn-success text-white mb-1">
                            <i class="icon-plus"></i>
                            New Payment
                        </a>
                        @endcan
                        <a href="{{ route('orders.invoice.latest', ['order' => $order,]) }}" target="_blank" class="btn btn-primary text-white mb-1">View Invoice</a>
                        {{-- TODO: Implement <button class="btn btn-primary text-white mb-1" onclick="alert('This is non-functional')">Email Invoice</button>--}}
                        {{-- TODO: Implement <button class="btn btn-primary text-white mb-1" onclick="alert('This is non-functional')">View Previous Invoices</button>--}}
                    </div>
                    <div class="pt-1">
                        <table class="table table-striped" id="payment-table">
                            <thead>
                                <tr>
                                    <th scope="col">Type</th>
                                    <th scope="col">Method</th>
                                    <th scope="col">Customer</th>
                                    <th scope="col">Value</th>
                                    <th scope="col">Paid</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            @foreach($order->payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_type }}</td>
                                    <td>{{ $payment->paymentMethod->name }}</td>
                                    <td>{{ $payment->customer->full_name }}</td>
                                    <td>{{ f_currency($payment->amount) }}</td>
                                    <td>{{ f_datetime($payment->paid_on) }}</td>
                                    <td class="actions">
                                        @can('update', \App\Models\Order\Payment\Payment::class)
                                        <a href="{{ route('payments.edit', ['order' => $order, 'payment' => $payment,]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-note"></i>
                                            </span>
                                        @endcan
                                        @can('delete', \App\Models\Order\Payment\Payment::class)
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
                            @foreach($order->orderCustomers()->where('is_charged', '=', 1)->get() as $ordersCustomer)
                            <tr>
                                <td>Base: {{ $ordersCustomer->customer->first_name . ' ' . $ordersCustomer->customer->last_name }}</td>
                                <td>{{ f_currency($ordersCustomer->tour_cost) }}</td>
                            </tr>
                            @if($ordersCustomer->has_surcharge)
                                <tr>
                                    <td>Single Occupancy Surcharge: {{ $ordersCustomer->customer->full_name }}</td>
                                    <td>{{ f_currency($ordersCustomer->single_occupancy_surcharge) }}</td>
                                </tr>
                            @endif
                            @endforeach
                            @foreach($order->getAdditionalCosts()['upgrades'] as $upgrade)
                                <tr>
                                    <td>Upgrade: {{ $upgrade['description'] }}</td>
                                    <td>{{ f_currency($upgrade['upgrade']->cost) }}</td>
                                </tr>
                            @endforeach
                            @foreach($order->getAdditionalCosts()['addons'] as $addon)
                                <tr>
                                    <td>Add-on: {{ $addon['description'] }}</td>
                                    <td>{{ f_currency($addon['addon']->cost) }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <h4 class="fw-bold">Schedule</h4>
                    </div>
                    <div class="pb-3 text-end">
                        @can('update', \App\Models\Order\Order::class)
                            <a href="{{ route('order-installments.create', ['order' => $order, ]) }}" class="btn btn-success text-white mb-1">
                                <i class="icon-plus"></i>
                                New Installment
                            </a>
                            <a href="{{ route('order-installments.resync', ['order' => $order, ]) }}" class="btn btn-warning mb-1">
                                <i class="icon-refresh"></i>
                                Refresh from Tour
                            </a>
                        @endcan
                    </div>
                    <div>
                        <table class="table table-striped" id="schedule-table">
                            <thead>
                            <tr>
                                <th scope="col">Type</th>
                                <th scope="col">Due</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Outstanding</th>
                                <th scope="col" class="actions">Actions</th>
                            </tr>
                            </thead>
                            <tr>
                                <th scope="row">Deposit</th>
                                <td>With Order</td>
                                <td>{{ f_currency($order->calculated_deposit) }} ({{ $order->deposit_percentage }}%)</td>
                                <td>
                                    @php $amount = $order->calculated_deposit - min($order->paid, $order->calculated_deposit); @endphp
                                    @if($amount <= 0)
                                        Paid
                                    @else
                                        {{ f_currency($amount) }}
                                    @endif
                                </td>
                                <td class="actions">
                                    <a href="{{route('orders.edit', ['order' => $order,])}}" class="btn btn-outline-success btn-sm mb-1">
                                        <i class="icon-note"></i>
                                    </a>
                                </td>
                            </tr>
                            @foreach($order->installments as $installment)
                                @php $paid = $installment->repository->getAmountPaid(); @endphp
                                <tr>
                                    <th scope="row">Installment</th>
                                    <td>{{ f_date($installment->due_on) }}</td>
                                    <td>{{ f_currency($installment->calculated_amount) }} ({{ $installment->percentage }}%)</td>
                                    <td>                                    @php $amount = $installment->calculated_amount - $installment->repository->getAmountPaid(); @endphp
                                        @if($amount <= 0)
                                            Paid
                                        @else
                                            {{ f_currency($amount) }}
                                        @endif</td>
                                    <td class="actions">
                                        <a href="{{route('order-installments.edit', ['order' => $order, 'orderInstallment' => $installment,])}}" class="btn btn-outline-success btn-sm mb-1">
                                            <i class="icon-note"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline-danger btn-sm mb-1"
                                           onclick="event.preventDefault();document.getElementById('orderInstallment-{{ $installment->id }}-delete').submit();">
                                            <i class="icon-trash"></i>
                                        </a>
                                        <form id="orderInstallment-{{ $installment->id }}-delete"
                                              action="{{ route('order-installments.delete', ['order' => $order, 'orderInstallment' => $installment,]) }}"
                                              method="POST" style="display: none;">{{ csrf_field() }}</form>
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <th scope="row">Remaining Balance</th>
                                <td>{{ f_date($order->tour->final_payment) }}</td>
                                <td>{{ f_currency($order->remaining_installment) }} ({{ $order->remaining_percentage }}%)</td>
                                <td>
                                    @php $amount = min($order->remaining, $order->remaining_installment); @endphp
                                    @if($amount <= 0)
                                        Paid
                                    @else
                                        {{ f_currency($amount) }}
                                    @endif
                                </td>
                                <td class="actions">
                                    <a href="{{route('tours.edit', ['tour' => $order->tour,])}}" class="btn btn-outline-success btn-sm mb-1">
                                        <i class="icon-note"></i>
                                    </a>
                                </td>
                            </tr>
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
                    @can('create', \App\Models\Order\Adjustment\ManualAdjustment::class)
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
                                    <td>{{ f_currency($adjustment->amount) }}</td>
                                    <td>{{ $adjustment->reason }}</td>
                                    <td class="actions">
                                        @can('update', \App\Models\Order\Adjustment\ManualAdjustment::class)
                                            <a href="{{ route('manual-adjustments.edit', ['order' => $order, 'manualAdjustment' => $adjustment,]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-note"></i>
                                            </span>
                                        @endcan
                                        @can('delete', \App\Models\Order\Adjustment\ManualAdjustment::class)
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
                            @foreach($order->orderCustomers as $ordersCustomer)
                                @foreach($ordersCustomer->adjustments as $adjustment)
                                <tr>
                                    <td>{{ $ordersCustomer->customer->first_name .  " " . $ordersCustomer->customer->last_name }}</td>
                                    <td>{{ f_currency($adjustment->amount) }}</td>
                                    <td>{{ $adjustment->reason }}</td>
                                    <td class="actions">
                                        @can('update', \App\Models\Order\Adjustment\OrderCustomerAdjustment::class)
                                        <a href="{{ route('order-customer-adjustments.edit', ['order' => $order, 'orderCustomer' => $ordersCustomer, 'orderCustomerAdjustment' => $adjustment,]) }}" class="btn btn-outline-primary btn-sm mb-1"><i class="icon-note"></i></a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1">
                                                <i class="icon-note"></i>
                                            </span>
                                        @endcan
                                        @can('delete', \App\Models\Order\Adjustment\OrderCustomerAdjustment::class)
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
