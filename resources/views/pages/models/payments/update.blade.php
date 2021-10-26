@extends('layout.main')

@section('title', 'Update Payment')

@section('content')
    @include('partials.models.payments.form', ['action' => route('payments.update', ['order' => $order, 'payment' => $payment,]),
      'payment_method_id' => $payment->payment_method_id,
      'amount' => $payment->amount,
      'reason' => $payment->reason,
    ])
@endsection
