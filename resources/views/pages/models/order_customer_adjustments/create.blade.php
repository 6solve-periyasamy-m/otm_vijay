@extends('layout.form', ['action' => route('order-customer-adjustments.store', ['order' => $order, 'orderCustomer' => $orderCustomer, ]),])

@section('title', 'Create Order Customer Adjustment')

@section('form-body')
    @include('partials.models.order_customer_adjustments.form')
@endsection
