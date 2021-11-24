@include('partials.fields.selector.default', ['name' => 'Payment Method', 'field' => 'payment_method_id', 'value' => $payment_method_id ?? 0, 'route' => 'payment-method',])
@include('partials.fields.text', ['name' => 'Amount', 'field' => 'amount', 'value' => $amount ?? null])
@include('partials.fields.datetime', ['name' => 'Paid On', 'field' => 'paid_on', 'value' => $paid_on ?? null])
@include('partials.fields.dropdown', ['name' => 'Payment Type', 'field' => 'payment_type', 'values' => [
    'Deposit' => 'Deposit',
    'Installment' => 'Installment',
    'Refund' => 'Refund'
], 'selected' => $payment_type ?? null,])
@include('partials.fields.submit')
