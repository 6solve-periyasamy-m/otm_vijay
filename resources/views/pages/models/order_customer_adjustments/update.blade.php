@extends('layout.main')

@section('title', 'Update Order Customer Adjustments')

@section('content')
    @include('partials.models.order_customer_adjustments.form', ['action' => route('order-customer-adjustments.update', ['order' => $order, 'orderCustomer' => $orderCustomer, 'orderCustomerAdjustment' => $orderCustomerAdjustment,]),
      'order_customer_id' => $orderCustomerAdjustment->order_customer_id,
      'amount' => $orderCustomerAdjustment->amount,
      'reason' => $orderCustomerAdjustment->reason,
    ])
@endsection
