@extends('layout.master')

@section('title', 'Create Customer')

@section('content')
    @include('partials.models.customers.form', ['action' => route('customers.store'),])
@endsection
