@extends('layout.main')

@section('title', 'View Payment Method')

@section('content')
    Name: {{ $paymentMethod->name }}<br/>
@endsection
