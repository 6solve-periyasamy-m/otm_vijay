@extends('layout.form', ['action' => route('order-installments.update', ['order' => $order, 'orderInstallment' => $orderInstallment,]),])

@section('title', 'Update Order Installment')

@section('form-body')
  @include('partials.models.payment_installments.form', [
    'due_on' => $orderInstallment->due_on,
    'amount' => $orderInstallment->amount,
  ])
@endsection
