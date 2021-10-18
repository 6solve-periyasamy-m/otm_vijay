@extends('layout.main')

@section('title', 'View Operator')

@section('content')
    Name: {{ $operator->name }}<br/>
    Notes: {{ $operator->notes }}<br/>
@endsection
