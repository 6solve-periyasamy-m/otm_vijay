@extends('layout.main')

@section('title', 'Create Customers')

@section('content')
  @include('partials.models.customers.form', ['action' => route('customers.store'),])
@endsection
