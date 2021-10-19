@extends('layout.main')

@section('title', 'Create Payment Method')

@section('content')
    @include('partials.models.payment_methods.form', ['action' => route('payment-methods.store'),])
@endsection
