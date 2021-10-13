@extends('layout.main')

@section('title', 'View Payment Methods')

@section('content')
    Name: {{ $paymentMethod->name }}<br/>
@endsection
