@extends('layout.form', ['action' => route('payment-installments.store', ['tour' => $tour,]),])

@section('title', 'Create Payment Installment')

@section('form-body')
  @include('partials.models.payment_installments.form')
@endsection
