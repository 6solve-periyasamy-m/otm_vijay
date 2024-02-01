@php
    /**
     * @var \App\Models\Order\Order $order
     * @var \App\Models\Order\OrderInstallment|null $orderInstallment
     */
    $orderInstallment = $orderInstallment ?? null;
    $title = __('order.adjustment.form.title.' . ($orderInstallment === null ? 'create' : 'update'));
    $route = $orderInstallment === null ?
        route('order-installments.store', ['order' => $order,]) :
        route('order-installments.update', ['order' => $order, 'orderInstallment' => $orderInstallment,]);
@endphp

@extends('layout.form', ['action' => $route,])

@section('title', $title)

@section('form-body')
    @include('partials.fields.date', ['name' => 'Due On', 'field' => 'due_on', 'value' => $orderInstallment?->due_on, ])
    @include('partials.fields.text', ['name' => 'Amount', 'field' => 'amount', 'value' => $orderInstallment?->amount, ])
    @include('partials.fields.submit')
@endsection