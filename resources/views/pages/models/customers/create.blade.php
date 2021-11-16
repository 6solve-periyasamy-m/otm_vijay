@extends('layout.form', ['action' => route('customers.store'), 'multipart' => true])

@section('title', 'Create Customer')

@section('form-body')
    @include('partials.models.customers.form')
@endsection
