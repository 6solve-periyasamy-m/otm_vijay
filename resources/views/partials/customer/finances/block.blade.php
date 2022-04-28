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
                    <div class="col-md-4">
                        <p class="payment-value">{{ StringFormatter::formatCurrency($order->total) }}</p>
                        <label class="payment-label">Total Order Value</label>
                    </div>
                    <div class="col-md-4">
                        <p class="payment-value">{{ StringFormatter::formatCurrency($order->remaining) }}</p>
                        <label class="payment-label">Balance Outstanding</label>
                    </div>
                    <div class="col-md-4">
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
                <div class="text-center">
                    <div class="row payment-value table-header">
                        <div class="col-3">
                            Type
                        </div>
                        <div class="col-3">
                            Paid On
                        </div>
                        <div class="col-3">
                            Amount Paid
                        </div>
                        <div class="col-3">
                            Method
                        </div>
                    </div>
                    @foreach($order->payments as $payment)
                        <div class="row">
                            <div class="col-3">
                                {{ $payment->payment_type }}
                            </div>
                            <div class="col-3">
                                {{ StringFormatter::formatDate($payment->paid_on) }}
                            </div>
                            <div class="col-3">
                                {{ StringFormatter::formatCurrency($payment->amount) }}
                            </div>
                            <div class="col-3">
                                {{ $payment->paymentMethod }}
                            </div>
                        </div>
                    @endforeach
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
                <div class="text-center">
                    <div class="row payment-value table-header">
                        <div class="col-3">
                            Type
                        </div>
                        <div class="col-3">
                            Due By
                        </div>
                        <div class="col-3">
                            Amount Due
                        </div>
                        <div class="col-3">
                            Paid?
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3">
                            Deposit
                        </div><div class="col-3">
                            With Order
                        </div>
                        <div class="col-3">
                            {{ StringFormatter::formatCurrency($order->calculated_deposit) }}
                        </div>
                        <div class="col-3">
                            {{ StringFormatter::formatBoolean($order->calculated_deposit <= $order->paid) }}
                        </div>
                    </div>
                    @foreach($order->installments as $installment)
                        <div class="row">
                            <div class="col-3">
                                Instalment
                            </div>
                            <div class="col-3">
                                {{ StringFormatter::formatDate($installment->due_on) }}
                            </div>
                            <div class="col-3">
                                {{ StringFormatter::formatCurrency($installment->calculated_amount) }}
                            </div>
                            <div class="col-3">
                                {{ StringFormatter::formatBoolean($installment->paid) }}
                            </div>
                        </div>
                    @endforeach
                    <div class="row">
                        <div class="col-3">
                            Remaining
                        </div>
                        <div class="col-3">
                            {{ StringFormatter::formatDate($order->tour->final_payment) }}
                        </div>
                        <div class="col-3">
                            {{ StringFormatter::formatCurrency($order->remaining_installment) }}
                        </div>
                        <div class="col-3">
                            {{ StringFormatter::formatBoolean($order->remaining <= 0) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if($next['installment'] !== null && !$order->cancelled)
    <div class="col-12">
        <div class="card">
            <div class="card-body payment-balance">
                <div class="row">
                    <p class="heading">Next Payment Details</p>
                    <div class="col-md-6">
                        <p class="payment-value">
                            <a href="" class="text-dark" onclick="event.preventDefault();$('.amount-input').val({{$next['amount']}})">
                                {{ StringFormatter::formatCurrency($next['amount']) }}
                            </a>
                        </p>
                        <label class="payment-label">Amount due to fulfil next instalment</label>
                    </div>
                    <div class="col-md-6">
                        <p class="payment-value">{{ StringFormatter::formatDate($next['due']) }}</p>
                        <label class="payment-label">Due by</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
