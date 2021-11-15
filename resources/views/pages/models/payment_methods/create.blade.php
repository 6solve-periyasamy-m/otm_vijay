@extends('layout.form', ['action' => route('payment-methods.store'),])

@section('title', 'Create Payment Method')

@section('form-body')
    @include('partials.models.payment_methods.form')
@endsection
