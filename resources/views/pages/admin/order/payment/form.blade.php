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

@extends('layout.master', ['action' => $route,])

@section('title', $title . " - {$order->booking_reference}")

@section('content')
    <livewire:admin.order.payment.form :order="$order" :payment="$payment" />
@endsection
