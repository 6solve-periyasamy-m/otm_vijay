@extends('layout.form', ['action' => route('payment-installments.update', ['tour' => $tour, 'paymentInstallment' => $paymentInstallment,]),])

@section('title', 'Update PaymentInstallment')

@section('form-body')
  @include('partials.models.payment_installments.form', [
    'due_on' => $paymentInstallment->due_on,
    'amount' => $paymentInstallment->amount,
  ])
@endsection
