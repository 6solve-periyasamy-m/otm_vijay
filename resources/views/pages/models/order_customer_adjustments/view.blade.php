@extends('layout.main')

@section('title', 'View Order Customer Adjustment')

@section('content')
    Order Customer Id: {{ $orderCustomerAdjustment->order_customer_id }}<br/>
    Amount: {{ $orderCustomerAdjustment->amount }}<br/>
    Reason: {{ $orderCustomerAdjustment->reason }}<br/>
@endsection
