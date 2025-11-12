@php
/**
 * @var App\Models\Customer\Customer|null $customer
 */
$customer = $customer ?? null;
$title = ($customer === null ? 'Create' : 'Update') . " Customer"
@endphp

@extends('layout.master')

@section('title', $title)

@section('content')
    <livewire:admin.customer.form :customer="$customer" />
@endsection