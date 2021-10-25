@extends('layout.main')

@section('title', 'View Country')

@section('content')
    Name: {{ $country->name }}<br/>
    Code: {{ $country->code }}<br/>
    Currency: {{ $country->currency }}<br/>
@endsection
