@extends('layout.main')

@section('title', 'Create Orders')

@section('content')
    @include('partials.models.orders.form', ['action' => route('orders.store'),])
@endsection
