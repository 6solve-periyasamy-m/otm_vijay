@extends('layout.master')

@section('title', 'Update PaymentInstallment')

@section('content')
  @include('partials.models.payment_installments.form', ['action' => route('payment-installments.update', ['tour' => $tour, 'paymentInstallment' => $paymentInstallment,]),
    'due_on' => $paymentInstallment->due_on,
    'amount' => $paymentInstallment->amount,
  ])
@endsection
