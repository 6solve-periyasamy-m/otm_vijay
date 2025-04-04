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
    function resend() {
        hideOverlay('.panel-overlay')
        $.post('{{ route('api.order.resend.booking-confirmation') }}', {
            '__api_token': '{{ Auth::user()->getCurrentToken()->token }}',
            'order': {{ $order->id }},
        }).done(function (xhr, textStatus, errorThrown) {
            console.log(xhr);
            if (xhr.success) {
                showToast('Resent Successfully', xhr.message, 'success');
            } else {
                showToast('Resend Failed', xhr.message, 'danger');
            }
        }).fail(function (xhr, textStatus, errorThrown) {
            showToast('Resend Failed', xhr.responseText, 'danger');
        });
    }
    function updateInvoice(selector) {
        let url = "{{ route('orders.invoice', ['order' => $order, 'version' => '#replace#']) }}"
        $('.invoice-button').prop('href', url.replace('#replace#', $(selector).val()))
    }
    function showPopupModal() {
        return openModal('admin.order.controls', {'order': {{$order->id}},});
    }
</script>
@endsection

@section('content')
@include('partials.admin.order.popup')
{{-- Header Details --}}
<div class="otm-callout" id="header-details">
    <div class="row">
        <div class="col-12 col-xl-6">
            <p>Booking Reference</p>
            <h6 class="fw-bold">{{ $order->booking_reference }}</h6>
        </div>
        @isset ($order->tour?->event?->name)
            <div class="col-12 col-xl-6">
                <p>Event</p>
                <h6 class="fw-bold">{{ $order->tour->event->name }}</h6>
            </div>
        @endisset
        <div class="col-12 col-xl-6">
            <p>Tour</p>
            <h6 class="fw-bold">{{ $order->tour?->name ?? "Tour Deleted" }}</h6>
        </div>
        <div class="col-12 col-xl-4">
            <p>Tour Date</p>
            <h6 class="fw-bold">{{ $order->tour !== null ? f_date($order->tour->date_from) . " to " . f_date($order->tour->date_to) : "Tour Deleted" }}</h6>
        </div>
        <div class="col-12 col-xl-2">
            <p>Order Status</p>
            <h6 class="badge badge-{{ $order->status->color() }} fw-bold">{{ $order->status->description() }}</h6>
        </div>                
        <div class="col-12 col-xl-3">
            <p>Order Value</p>
            <h6 class="fw-bold">
                @if($order->cancelled)
                    {{ f_currency($order->total) }} ({{ f_currency($order->cost) }} before cancellation)
                @else
                    {{ f_currency($order->total) }}
                    @if ($order->repository->getBeforeString() !== null)
                        ({{ $order->repository->getBeforeString() }})
                    @endif
                @endif
            </h6>
        </div>
        <div class="col-12 col-xl-3">
            <p>Total Paid</p>
            <h6 class="fw-bold">{{ f_currency($order->paid) }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Balance Outstanding</p>
            <h6 class="fw-bold">{{ f_currency($order->remaining) }}</h6>
        </div>
        <div class="col-12 col-xl-3">
            <p>Next Payment Due</p>
            <h6 class="fw-bold">{{ $order->next_installment !== null ? f_date($order->next_installment->due_on) . ' - ' . f_currency($order->next_installment->remaining) : 'All installments paid' }}</h6>
        </div>
        <div class="col-12 col-xl-3">
            <p>Tax Amount</p>
            <h6 class="fw-bold">{{ $order->getTaxes() !== null ? f_currency($order->getTaxes()) : 'No Taxes Due' }}</h6>
        </div>
        <div class="col-12 col-xl-3">
            <p>Cost to Company</p>
            <h6 class="fw-bold">{{ f_currency($order->repository->getCostToCompany())}}</h6>
        </div>
        <div class="col-12 col-xl-3">
            <p>Current Profit</p>
            <h6 class="fw-bold">{{ f_currency($order->cache->profit)}}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Lead Booker</p>
            <h6 class="fw-bold">{{ $order->leadBooker->customer_name }}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Consultant</p>
            <h6 class="fw-bold">
                @if ($order->consultant !== null)
                    {{ $order->consultant->name }} ({{ $order->consultant->email }})
                @else
                    No Consultant Set
                @endif
            </h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>Internal Notes</p>
            <h6 class="fw-bold">{!! nl2br($order->internal_notes) !!}</h6>
        </div>
        <div class="col-12 col-xl-6">
            <p>External Notes</p>
            <h6 class="fw-bold">{!! nl2br($order->external_notes) !!}</h6>
        </div>
        @if($order->organization_id)
        <div class="col-12 col-xl-6">
            <p>Organization</p>
            <h6 class="fw-bold">{!! $order->organization->name !!}</h6>
        </div>
        @endif
        @if($order->agent_id)
        <div class="col-12 col-xl-6">
            <p>Agent</p>
            <h6 class="fw-bold">{!! $order->agent->first_name . ' ' . $order->agent->last_name !!}</h6>
        </div>
        @endif
        <div class="col-12">
            <a href="{{ route('orders.edit', ['order' => $order,]) }}" class="btn btn-success">
                {{ Icon::edit() }}
                Edit Order
            </a>
            <button type="button" class="btn btn-secondary" onclick="showPopupModal()">
                {{ Icon::options() }}
                <span>Options</span>
            </button>
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
                {{ Icon::create() }}
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
                    <h6 class="fw-bold">{{ isset($ordersCustomer->customer->date_of_birth) ? f_date($ordersCustomer->customer->date_of_birth) : 'Date of Birth not set' }}</h6>
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
            <x-admin.section.card>
                <x-slot:title>
                    Payments
                </x-slot:title>
                <div class="pb-3 row">
                    <div class="col-7">
                        <select class="form-select" onchange="updateInvoice(this)">
                            <option value="latest" selected>Latest</option>
                            @foreach($order->invoices as $invoice)
                                <option value="{{ $invoice->invoice_number }}">Invoice #{{$invoice->invoice_number}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <a href="{{ route('orders.invoice', ['order' => $order,]) }}" target="_blank" class="invoice-button btn btn-primary text-white mb-1">View Invoice</a>
                    </div>
                    @can('create', \App\Models\Order\Payment\Payment::class)
                        <div class="col-3">
                            <a href="{{ route('payments.create', ['order' => $order, ]) }}" class="btn btn-success text-white mb-1">
                                {{ Icon::create() }}
                                New Payment
                            </a>
                        </div>
                    @endcan
                </div>
                <div class="pt-1">
                    <table class="datatable table" id="payment-table" data-ordering="false">
                        <thead>
                        <tr>
                            <th scope="col">Type</th>
                            <th scope="col">Method</th>
                            <th scope="col">Payer</th>
                            <th scope="col">Value</th>
                            <th scope="col">Paid</th>
                            <th scope="col">Notes</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        @foreach($order->payments as $index => $payment)
                            @php
                                $row_class = $index % 2 == 0 ? 'odd-grp' : 'even-grp';
                            @endphp
                            <tr class="{{ $row_class }}">
                                <td>{{ $payment->payment_type }}</td>
                                <td>{{ $payment->paymentMethod->name }}</td>
                                <td>{{ $payment->payer_name ?? "No Customer Found" }}</td>
                                <td>{{ f_currency($payment->amount) }}</td>
                                <td>{{ f_datetime($payment->paid_on) }}</td>
                                @php
                                    $rowspan = $payment->payment_fee ? 2 : 1;
                                    $internalNotes = $payment->internal_notes ?? " -Nil- ";
                                    $fee = $payment->payment_fee ? f_currency($payment->payment_fee) : null;
                                @endphp

                                <td class="w-15 align-middle text-justify" rowspan="{{ $rowspan }}">
                                    {{ $internalNotes }}
                                </td>

                                <td class="actions align-middle" rowspan="{{ $rowspan }}">
                                    @can('update', \App\Models\Order\Payment\Payment::class)
                                        <a href="{{ route('payments.edit', ['order' => $order, 'payment' => $payment,]) }}"
                                           class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1" title="Edit">
                                                {{ Icon::edit() }}
                                            </span>
                                    @endcan
                                    @can('delete', \App\Models\Order\Payment\Payment::class)
                                        <a href="#" onclick="$('#payment-{{$payment->id}}-delete').submit()"
                                           class="btn btn-outline-danger btn-sm mb-1" title="Delete">{{ Icon::delete() }}</a>
                                        <form action="{{ route('payments.delete', ['order' => $order, 'payment' => $payment,]) }}"
                                              method="post" id="payment-{{$payment->id}}-delete">
                                            @csrf
                                        </form>
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                                {{ Icon::delete() }}
                                            </span>
                                    @endcan
                                </td>
                            </tr>
                            @if($payment->payment_fee !== null)
                                <tr class="{{ $row_class }}">
                                    <td>Payment Fee</td>
                                    <td>{{ $payment->paymentMethod->name }} Fee</td>
                                    <td>{{ $payment->payer_name ?? "No Customer Found" }}</td>
                                    <td>{{ $fee }}</td>
                                    <td>{{ f_datetime($payment->paid_on) }}</td>
                                    <td hidden></td>
                                    <td hidden></td>
                                </tr>
                            @endif
                        @endforeach
                    </table>
                </div>
            </x-admin.section.card>
            <x-admin.section.card>
                <x-slot:title>
                    Sell Price Per Person
                </x-slot:title>
                <div>
                    <table class="datatable table table-striped" id="cost-table">
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
            </x-admin.section.card>
            <x-admin.section.card>
                <x-slot:title>
                    Schedule
                </x-slot:title>
                <div class="pb-3 text-end">
                    @can('update', \App\Models\Order\Order::class)
                        <a href="{{ route('order-installments.create', ['order' => $order, ]) }}" class="btn btn-success text-white mb-1">
                            {{ Icon::create() }}
                            New Installment
                        </a>
                        <a href="{{ route('order-installments.resync', ['order' => $order, ]) }}" class="btn btn-warning mb-1">
                            {{ Icon::refresh() }}
                            Refresh from Tour
                        </a>
                    @endcan
                </div>
                <div>
                    <table class="datatable table table-striped" id="schedule-table">
                        <thead>
                        <tr>
                            <th scope="col">Type</th>
                            <th scope="col">Due</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Received</th>
                            <th scope="col">Outstanding</th>
                            <th scope="col">Paid On</th>
                            <th scope="col" class="actions">Actions</th>
                        </tr>
                        </thead>
                        @if(($order->booking_fee ?? 0) > 0)
                            <tr>
                                <th scope="row">Booking Fee</th>
                                <td>With Order</td>
                                <td>{{ f_currency($order->booking_fee) }}</td>
                                <td>
                                    {{ f_currency(min($order->booking_fee, $order->paid)) }}
                                </td>
                                <td>
                                    @if($order->booking_fee <= $order->paid)
                                        Paid
                                    @else
                                        {{ f_currency($order->booking_fee - min($order->booking_fee, $order->paid)) }}
                                    @endif
                                </td>
                                <td>
                                    @php $covering = $order->repository->getBookingFeePayment(); @endphp
                                    {{ $covering !== null ? f_datetime($covering->paid_on) : "Not Paid" }}
                                </td>
                                <td class="actions">
                                    <a href="{{route('orders.edit', ['order' => $order,])}}" title="Edit" class="btn btn-outline-success btn-sm mb-1">
                                        {{ Icon::edit() }}
                                    </a>
                                </td>
                            </tr>
                        @endif
                        @if(($order->calculated_deposit ?? 0) > 0)
                            <tr>
                                <th scope="row">Deposit</th>
                                <td>With Order</td>
                                <td>{{ f_currency($order->calculated_deposit) }} ({{ $order->deposit_percentage }}%)</td>
                                <td>
                                    @php $amount = $order->calculated_deposit - min(($order->paid - ($order->booking_fee ?? 0)), $order->calculated_deposit); @endphp
                                    @if($amount <= 0)
                                        {{ f_currency($order->calculated_deposit) }}
                                    @else
                                        {{ f_currency($order->paid) }}
                                    @endif
                                </td>
                                <td>
                                    @if($amount <= 0)
                                        Paid
                                    @else
                                        {{ f_currency($amount) }}
                                    @endif
                                </td>
                                <td>
                                    @php $covering = $order->repository->getDepositPayment(); @endphp
                                    {{ $covering !== null ? f_datetime($covering->paid_on) : "Not Paid" }}
                                </td>
                                <td class="actions">
                                    <a href="{{route('orders.edit', ['order' => $order,])}}" title="Edit" class="btn btn-outline-success btn-sm mb-1">
                                        {{ Icon::edit() }}
                                    </a>
                                </td>
                            </tr>
                        @endif
                        @foreach($order->installments as $installment)
                            @php $paid = $installment->repository->getAmountPaid(); @endphp
                            @php $amount = $installment->calculated_amount - $installment->repository->getAmountPaid(); @endphp
                            <tr>
                                <th scope="row">Installment</th>
                                <td>{{ f_date($installment->due_on) }}</td>
                                <td>{{ f_currency($installment->calculated_amount) }} ({{ $installment->percentage }}%)</td>
                                <td>
                                    @if($amount <= 0)
                                        {{ f_currency($installment->calculated_amount) }}
                                    @else
                                        {{ f_currency($installment->repository->getAmountPaid()) }}
                                    @endif
                                </td>
                                <td>
                                    @if($amount <= 0)
                                        Paid
                                    @else
                                        {{ f_currency($amount) }}
                                    @endif
                                </td>
                                <td>
                                    @php $paidOn = $installment->paid_on @endphp
                                    {{ $paidOn === null ? 'Not Paid' : f_datetime($paidOn) }}
                                </td>
                                <td class="actions">
                                    <a href="{{route('order-installments.edit', ['order' => $order, 'orderInstallment' => $installment,])}}" title="Edit" class="btn btn-outline-success btn-sm mb-1">
                                        {{ Icon::edit() }}
                                    </a>
                                    <a href="#" class="btn btn-outline-danger btn-sm mb-1" title="Delete"
                                       onclick="event.preventDefault();document.getElementById('orderInstallment-{{ $installment->id }}-delete').submit();">
                                        {{ Icon::delete() }}
                                    </a>
                                    <form id="orderInstallment-{{ $installment->id }}-delete"
                                          action="{{ route('order-installments.delete', ['order' => $order, 'orderInstallment' => $installment,]) }}"
                                          method="POST" style="display: none;">{{ csrf_field() }}</form>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <th scope="row">Remaining Balance</th>
                            <td>{{ f_date($order->tour?->final_payment) }}</td>
                            <td>{{ f_currency($order->remaining_installment) }} ({{ $order->remaining_percentage }}%)</td>
                            <td> {{ f_currency($order->paid) }} </td>
                            <td>
                                @php $amount = min($order->remaining, $order->remaining_installment); @endphp
                                @if($amount <= 0)
                                    Paid
                                @else
                                    {{ f_currency($amount) }}
                                @endif
                            </td>
                            <td>
                                @php $covering = $order->repository->getRemainingPayment(); @endphp
                                {{ $covering !== null ? f_datetime($covering->paid_on) : "Not Paid" }}
                            </td>
                            <td class="actions">
                                @if($order->tour !== null)
                                <a href="{{route('tours.edit', ['tour' => $order->tour,])}}" title="Edit" class="btn btn-outline-success btn-sm mb-1">
                                    {{ Icon::edit() }}
                                </a>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </x-admin.section.card>
        </div>
        <div class="col-xl-6">
            <x-admin.section.card>
                <x-slot:title>
                    Order Adjustments
                </x-slot:title>
                @can('create', \App\Models\Order\Adjustment\ManualAdjustment::class)
                    <div class="pb-3 text-end">
                        <a href="{{ route('manual-adjustments.create', ['order' => $order, ]) }}" class="btn btn-success text-white">
                            {{ Icon::create() }}
                            Add Adjustment
                        </a>
                    </div>
                @endcan
                <div class="pt-2">
                    <table class="datatable table table-striped" id="order-adjustment-table">
                        <thead>
                        <tr>
                            <th scope="col">Amount</th>
                            <th scope="col">Reason</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                            @if($order->commission !== null)
                                <tr>
                                    <td>{{ f_currency($order->commission_amount) }}</td>
                                    <td>Commission: {{ $order->commission }}%</td>
                                    <td class="actions">
                                        <a href="{{ route('orders.edit', ['order' => $order,]) }}"
                                           class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                                    </td>
                                </tr>
                            @endif
                            @foreach($order->adjustments as $adjustment)
                            <tr>
                                <td>{{ f_currency($adjustment->amount) }}</td>
                                <td>{{ $adjustment->reason }}</td>
                                <td class="actions">
                                    @can('update', \App\Models\Order\Adjustment\ManualAdjustment::class)
                                        <a href="{{ route('manual-adjustments.edit', ['order' => $order, 'manualAdjustment' => $adjustment,]) }}"
                                           class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1" title="Edit">
                                                {{ Icon::edit() }}
                                            </span>
                                    @endcan
                                    @can('delete', \App\Models\Order\Adjustment\ManualAdjustment::class)
                                        <a href="#" onclick="$('#madjustment-{{$adjustment->id}}-delete').submit()"
                                           class="btn btn-outline-danger btn-sm mb-1" title="Delete">{{ Icon::delete() }}</a>
                                        <form action="{{ route('manual-adjustments.delete', ['order' => $order, 'manualAdjustment' => $adjustment,]) }}"
                                              method="post" id="madjustment-{{$adjustment->id}}-delete">
                                            @csrf
                                        </form>
                                    @else
                                        <span class="btn btn-outline-dark btn-sm mb-1">
                                                {{ Icon::delete() }}
                                            </span>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </x-admin.section.card>
            <x-admin.section.card>
                <x-slot:title>
                    Customer Adjustments
                </x-slot:title>
                <div>
                    <table class="datatable table table-striped" id="customer-adjustment-table">
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
                                            <a href="{{ route('order-customer-adjustments.edit', ['order' => $order, 'orderCustomer' => $ordersCustomer, 'orderCustomerAdjustment' => $adjustment,]) }}"
                                               class="btn btn-outline-primary btn-sm mb-1" title="Edit">{{ Icon::edit() }}</a>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1" title="Edit">
                                                {{ Icon::edit() }}
                                            </span>
                                        @endcan
                                        @can('delete', \App\Models\Order\Adjustment\OrderCustomerAdjustment::class)
                                            <a href="#"
                                               onclick="$('#oadjustment-{{$adjustment->id}}-delete').submit()"
                                               class="btn btn-outline-danger btn-sm mb-1" title="Delete">{{ Icon::delete() }}</a>
                                            <form action="{{ route('order-customer-adjustments.delete', ['order' => $order, 'orderCustomer' => $ordersCustomer, 'orderCustomerAdjustment' => $adjustment,]) }}"
                                                  method="post" id="oadjustment-{{$adjustment->id}}-delete">
                                                @csrf
                                            </form>
                                        @else
                                            <span class="btn btn-outline-dark btn-sm mb-1" title="Delete">
                                                {{ Icon::delete() }}
                                            </span>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </x-admin.section.card>
        </div>
    </div>
</div>

{{-- Closing Container--}}
@endsection
