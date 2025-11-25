@extends('layout.customer')

@section('title', 'Edit Customer Profile')
@section('content')
    <livewire:customer.details-form :customer="$customer" />
@endsection