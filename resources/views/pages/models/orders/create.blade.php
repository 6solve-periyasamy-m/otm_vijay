@extends('layout.main')

@section('title', 'Create Order')

@section('content')
    @include('partials.models.orders.forms.create', ['action' => route('orders.store'),])
@endsection
