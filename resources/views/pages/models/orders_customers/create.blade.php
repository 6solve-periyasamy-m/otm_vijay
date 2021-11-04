@extends('layout.master')

@section('title', 'Create Orders Customer')

@section('content')
    @include('partials.models.orders_customers.form', ['action' => route('orders-customers.store', ['order' => $order, ]),])
@endsection
