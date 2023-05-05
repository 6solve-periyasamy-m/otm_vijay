@php
/**
 * @var \App\Models\Customer\Customer|null $customer
 */
$customer = $customer ?? null;
@endphp
@extends('layout.form', ['action' => route('customers.store'), 'multipart' => true, 'autocomplete' => false,])

@section('title', 'Create Customer')

@section('form-body')
    @include('partials.models.customers.form')
@endsection
