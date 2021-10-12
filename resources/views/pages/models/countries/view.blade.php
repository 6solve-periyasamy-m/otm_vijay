@extends('layout.main')

@section('title', 'View Countries')

@section('content')
    Name: {{ $country->name }}<br/>
    Code: {{ $country->code }}<br/>
    Currency: {{ $country->currency }}<br/>
@endsection
