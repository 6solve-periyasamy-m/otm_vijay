@extends('layout.main')

@section('title', 'Update Payment Methods')

@section('content')
    @include('partials.models.payment_methods.form', ['action' => route('payment-methods.update', ['paymentMethod' => $paymentMethod,]),
      'name' => $paymentMethod->name,
    ])
@endsection
