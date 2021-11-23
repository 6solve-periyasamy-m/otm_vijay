@extends('layout.form', ['action' => route('order-customer-adjustments.update', ['order' => $order, 'orderCustomer' => $orderCustomer, 'orderCustomerAdjustment' => $orderCustomerAdjustment,]),])

@section('title', 'Update Order Customer Adjustment')

@section('form-body')
    @include('partials.models.order_customer_adjustments.form', [
      'order_customer_id' => $orderCustomerAdjustment->order_customer_id,
      'amount' => $orderCustomerAdjustment->amount,
      'reason' => $orderCustomerAdjustment->reason,
      'date' => $manualAdjustment->date,
    ])
@endsection
