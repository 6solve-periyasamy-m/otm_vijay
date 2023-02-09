@php
/**
 * @var \App\Models\Order\Order $order
 */
$next = $order->next_installment;
@endphp
<div class="order order-{{ $order->booking_reference }}">
    <div class="col-12">
        <div class="card">
            <div class="card-body payment-balance">
                <div class="row">
                    <p class="heading">Payment Balance</p>
                    <div class="col-md-3 text-center">
                        <p class="payment-value">{{ $order->tour->name }}</p>
                    </div>
                    <div class="col-md-3 text-center">
                        <p class="payment-value">{{ f_currency($order->total) }}</p>
                        <label class="payment-label">Total Order Value</label>
                    </div>
                    <div class="col-md-3 text-center">
                        <p class="payment-value">{{ f_currency($order->remaining) }}</p>
                        <label class="payment-label">Balance Outstanding</label>
                    </div>
                    <div class="col-md-3 text-center">
                        <p class="payment-value" id="order_status">{{ $order->repository->getOrderStatus(true)->description() }}</p>
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
                                <td data-content="Paid On" class="fw-bold">{{ f_date($payment->paid_on) }}</td>
                                <td data-content="Type">{{ $payment->payment_type }}</td>
                                <td data-content="Amount Paid">{{ f_currency($payment->amount) }}</td>
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
                            <th scope="col">Outstanding</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(($order->booking_fee ?? 0) > 0)
                        <tr>
                            <td data-content="Due By" class="fw-bold">With Order</td>
                            <td data-content="Type">Booking Fee</td>
                            <td data-content="Amount Due">{{ f_currency($order->booking_fee) }}</td>
                            <td data-content="Outstanding">
                                @php $amount = $order->booking_fee - min($order->paid, $order->booking_fee); @endphp
                                @if($amount <= 0)
                                    Paid
                                @else
                                    {{ f_currency($amount) }}
                                @endif
                            </td>
                        </tr>
                        @endif
                        @if(($order->deposit ?? 0) > 0)
                        <tr>
                            <td data-content="Due By" class="fw-bold">With Order</td>
                            <td data-content="Type">Deposit</td>
                            <td data-content="Amount Due">{{ f_currency($order->calculated_deposit) }}</td>
                            <td data-content="Outstanding">
                                @php $amount = $order->calculated_deposit - min(($order->paid - ($order->booking_fee ?? 0)), $order->calculated_deposit); @endphp
                                @if($amount <= 0)
                                    Paid
                                @else
                                    {{ f_currency($amount) }}
                                @endif
                            </td>
                        </tr>
                        @endif
                        @foreach($order->installments as $installment)
                            <tr>
                                <td data-content="Due By" class="fw-bold">{{ f_date($installment->due_on) }}</td>
                                <td data-content="Type">Instalment</td>
                                <td data-content="Amount Due">{{ f_currency($installment->calculated_amount) }}</td>
                                <td data-content="Outstanding">
                                    @php $amount = $installment->calculated_amount - $installment->repository->getAmountPaid(); @endphp
                                    @if($amount <= 0)
                                        Paid
                                    @else
                                        {{ f_currency($amount) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td data-content="Due By" class="fw-bold">{{ f_date($order->tour->final_payment) }}</td>
                            <td data-content="Type">Remaining</td>
                            <td data-content="Amount Due">{{ f_currency($order->remaining_installment) }}</td>
                            <td data-content="Outstanding">
                                @php $amount = min($order->remaining, $order->remaining_installment); @endphp
                                @if($amount <= 0)
                                    Paid
                                @else
                                    {{ f_currency($amount) }}
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if(isset($next) && !$order->cancelled)
    <div class="col-12">
        <div class="card">
            <div class="card-body payment-balance">
                <div class="row">
                    <p class="heading">Next Payment Details</p>
                    <div class="col-md-6 text-center">
                        <p class="payment-value">
                            <a href="" class="text-dark cursor-pointer payable-amount"  onclick="event.preventDefault();$('.amount-input').val({{$next->amount}})">
                                {{ f_currency($next->amount) }}
                            </a>
                        </p>
                        <label class="payment-label">Amount due to fulfil next instalment</label>
                    </div>
                    <div class="col-md-6 text-center">
                        <p class="payment-value">{{ f_date($next->due_on) }}</p>
                        <label class="payment-label">Due by</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
