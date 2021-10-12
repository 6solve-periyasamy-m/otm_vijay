@extends('layout.main')

@section('title', 'View Ticket Types')

@section('content')
    Name: {{ $ticketType->name }}<br/>
@endsection
