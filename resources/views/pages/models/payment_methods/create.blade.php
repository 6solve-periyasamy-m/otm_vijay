@extends('layout.main')

@section('title', 'Create Payment Methods')

@section('content')
  @include('partials.models.payment_methods.form', ['action' => route('payment_methods.store'),])
@endsection
