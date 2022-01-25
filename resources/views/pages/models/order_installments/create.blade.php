@extends('layout.form', ['action' => route('order-installments.store', ['tour' => $tour,]),])

@section('title', 'Create Order Installment')

@section('form-body')
  @include('partials.models.payment_installments.form')
@endsection
