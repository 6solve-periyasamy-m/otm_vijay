@extends('layout.form', ['action' => route('orders.store'),])

@section('title', 'Create Order')

@section('form-body')
    @include('partials.models.orders.form')
@endsection
