@extends('layout.main')

@section('title', 'Update Payments')

@section('content')
    @include('partials.models.payments.form', ['action' => route('payments.update', ['payment' => $payment,]),
      'order_id' => $payment->order_id,
      'payment_method_id' => $payment->payment_method_id,
      'amount' => $payment->amount,
      'reason' => $payment->reason,
    ])
@endsection
