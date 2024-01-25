@php
    /**
     * @var \App\Models\Order\Order $order
     * @var \App\Models\Order\OrderCustomer $orderCustomer
     * @var \App\Models\Order\Adjustment\OrderCustomerAdjustment|null $orderCustomerAdjustment
     */
    $orderCustomerAdjustment = $orderCustomerAdjustment ?? null;
    $title = __('order.customer.adjustment.form.title.' . ($orderCustomerAdjustment === null ? 'create' : 'update'));
    $route = $manualAdjustment === null ?
        route('order-customer-adjustments.store', ['order' => $order, 'orderCustomer' => $orderCustomer,]) :
        route('order-customer-adjustments.update', ['order' => $order, 'orderCustomer' => $orderCustomer, 'orderCustomerAdjustment' => $orderCustomerAdjustment,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.text', ['name' => 'Amount', 'field' => 'amount', 'value' => $orderCustomerAdjustment?->amount ?? null, 'width' => 6])
    @include('partials.fields.date', ['name' => 'Date', 'field' => 'date', 'value' => $orderCustomerAdjustment?->date ?? null, 'width' => 6])
    @include('partials.fields.text', ['name' => 'Reason', 'field' => 'reason', 'value' => $orderCustomerAdjustment?->reason ?? null,])
    @include('partials.fields.submit')
@endsection