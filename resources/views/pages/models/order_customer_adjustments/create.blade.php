@extends('layout.main')

@section('title', 'Create Order Customer Adjustments')

@section('content')
    @include('partials.models.order_customer_adjustments.form', ['action' => route('order-customer-adjustments.store', ['order' => $order, 'orderCustomer' => $orderCustomer, ]),])
@endsection
