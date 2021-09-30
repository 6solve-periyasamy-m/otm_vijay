@extends('layout.main')

@section('title', 'Create Orders Customers')

@section('content')
  @include('partials.models.orders_customers.form', ['action' => route('orders_customers.store'),])
@endsection
