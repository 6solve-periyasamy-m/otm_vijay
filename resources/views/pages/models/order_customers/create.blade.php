@extends('layout.form', ['action' => route('order-customers.store', ['order' => $order, ]),])

@section('title', 'Create Orders Customer')

@section('form-body')
    @include('partials.models.order_customers.form')
@endsection
