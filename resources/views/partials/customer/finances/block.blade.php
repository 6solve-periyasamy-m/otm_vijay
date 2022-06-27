@php
/**
 * @var \App\Models\Order $order
 */
$next = $order->getNextInstallment();
@endphp
<div class="order order-{{ $order->booking_reference }}">
    <div class="col-12">
        <div class="card">
            <div class="card-body payment-balance">
                <div class="row">
                    <p class="heading">Payment Balance</p>
                    <div class="col-md-4 text-center">
                        <p class="payment-value">{{ StringFormatter::formatCurrency($order->total) }}</p>
                        <label class="payment-label">Total Order Value</label>
                    </div>
                    <div class="col-md-4 text-center">
                        <p class="payment-value">{{ StringFormatter::formatCurrency($order->remaining) }}</p>
                        <label class="payment-label">Balance Outstanding</label>
                    </div>
                    <div class="col-md-4 text-center">
                        <p class="payment-value" id="order_status">{{ $order->status }}</p>
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
                    <p class="heading">Payments</p>
                </div>
                <table class="table no-header-line table-striped table-responsive-sm text-center table-mobile-sided">
                    <thead>
                        <tr>
                            <th scope="col" class="fw-bold">Paid On</th>
                            <th scope="col">Type</th>
                            <th scope="col">Amount Paid</th>
                            <th scope="col">Method</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->payments as $payment)
                            <tr>
                                <td data-content="Paid On" class="fw-bold">{{ StringFormatter::formatDate($payment->paid_on) }}</td>
                                <td data-content="Type">{{ $payment->payment_type }}</td>
                                <td data-content="Amount Paid">{{ StringFormatter::formatCurrency($payment->amount) }}</td>
                                <td data-content="Method">{{ $payment->paymentMethod }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row" id="payment_records">
                    <p class="heading">Payment Schedule</p>
                </div>
                <table class="table no-header-line table-striped table-responsive-sm text-center table-mobile-sided">
                    <thead>
                        <tr>
                            <th scope="col">Due By</th>
                            <th scope="col">Type</th>
                            <th scope="col">Amount Due</th>
                            <th scope="col">Paid?</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td data-content="Due By" class="fw-bold">With Order</td>
                            <td data-content="Type">Deposit</td>
                            <td data-content="Amount Due">{{ StringFormatter::formatCurrency($order->calculated_deposit) }}</td>
                            <td data-content="Paid?">{{ StringFormatter::formatBoolean($order->calculated_deposit <= $order->paid) }}</td>
                        </tr>
                        @foreach($order->installments as $installment)
                            <tr>
                                <td data-content="Due By" class="fw-bold">{{ StringFormatter::formatDate($installment->due_on) }}</td>
                                <td data-content="Type">Instalment</td>
                                <td data-content="Amount Due">{{ StringFormatter::formatCurrency($installment->calculated_amount) }}</td>
                                <td data-content="Paid?">{{ StringFormatter::formatBoolean($installment->paid) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td data-content="Due By" class="fw-bold">{{ StringFormatter::formatDate($order->tour->final_payment) }}</td>
                            <td data-content="Type">Remaining</td>
                            <td data-content="Amount Due">{{ StringFormatter::formatCurrency($order->remaining_installment) }}</td>
                            <td data-content="Paid?">{{ StringFormatter::formatBoolean($order->remaining <= 0) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if($next['installment'] !== null && !$order->cancelled)
    <div class="col-12">
        <div class="card">
            <div class="card-body payment-balance">
                <div class="row">
                    <p class="heading">Next Payment Details</p>
                    <div class="col-md-6 text-center">
                        <p class="payment-value">
                            <a href="" class="text-dark cursor-pointer payable-amount"  onclick="event.preventDefault();$('.amount-input').val({{$next['amount']}})">
                                {{ StringFormatter::formatCurrency($next['amount']) }}
                            </a>
                        </p>
                        <label class="payment-label">Amount due to fulfil next instalment</label>
                    </div>
                    <div class="col-md-6 text-center">
                        <p class="payment-value">{{ StringFormatter::formatDate($next['due']) }}</p>
                        <label class="payment-label">Due by</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
