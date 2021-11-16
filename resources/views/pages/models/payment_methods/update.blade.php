@extends('layout.form', ['action' => route('payment-methods.update', ['paymentMethod' => $paymentMethod,]),])

@section('title', 'Update Payment Method')

@section('form-body')
    @include('partials.models.payment_methods.form', [
      'name' => $paymentMethod->name,
    ])
@endsection
