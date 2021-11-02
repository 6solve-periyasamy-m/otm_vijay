@extends('layout.main')

@section('title', 'Create Orders Customer')

@section('content')
    @include('partials.models.order_customers.form', ['action' => route('order-customers.store', ['order' => $order, ]),])
@endsection
