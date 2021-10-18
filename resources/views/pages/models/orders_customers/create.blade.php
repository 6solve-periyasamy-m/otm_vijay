@extends('layout.main')

@section('title', 'Create Orders Customer')

@section('content')
    @include('partials.models.orders_customers.form', ['action' => route('orders-customers.store'),])
@endsection
