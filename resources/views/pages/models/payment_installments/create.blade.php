@extends('layout.main')

@section('title', 'Create Payment Installment')

@section('content')
  @include('partials.models.payment_installments.form', ['action' => route('payment-installments.store', ['tour' => $tour,]),])
@endsection
