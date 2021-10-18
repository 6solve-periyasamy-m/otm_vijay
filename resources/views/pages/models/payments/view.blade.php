@extends('layout.main')

@section('title', 'View Payment')

@section('content')
    Order Id: {{ $payment->order_id }}<br/>
    Payment Method Id: {{ $payment->payment_method_id }}<br/>
    Amount: {{ $payment->amount }}<br/>
    Reason: {{ $payment->reason }}<br/>
@endsection
