@php
    /**
     * @var \App\Models\Order\Order $order
     * @var \App\Models\Order\Payment\Payment|null $payment
     */
    $payment = $payment ?? null;
    $title = __('order.payment.form.title.' . ($payment === null ? 'create' : 'update'));
    $route = $payment === null ?
        route('payments.store', ['order' => $order,]) :
        route('payments.update', ['order' => $order, 'payment' => $payment,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.selector.adder',
                ['name' => 'Customer', 'field' => 'customer_id', 'value' => $payment?->customer_id ?? 0,
                 'route' => 'customers', 'createRoute' => route('customers.create'),])
    @include('partials.fields.selector.default', ['name' => 'Payment Method', 'field' => 'payment_method_id', 'value' => $payment?->payment_method_id ?? 0, 'route' => 'payment-method',])
    @include('partials.fields.text', ['name' => 'Amount', 'field' => 'amount', 'value' => $payment?->amount ?? null])
    @include('partials.fields.datetime', ['name' => 'Paid On', 'field' => 'paid_on', 'value' => $payment?->paid_on ?? null])
    @include('partials.fields.dropdown', ['name' => 'Payment Type', 'field' => 'payment_type', 'values' => [
        'Deposit' => 'Deposit',
        'Installment' => 'Installment',
        'Refund' => 'Refund'
    ], 'selected' => $payment?->payment_type ?? null,])
    @include('partials.fields.submit')

    @include('partials.fields.submit')
@endsection