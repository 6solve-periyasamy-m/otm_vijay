@extends('layout.main')

@section('title', 'Create Order')

@section('content')
    @include('partials.models.orders.form', ['action' => route('orders.store'),])
@endsection
