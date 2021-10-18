@extends('layout.main')

@section('title', 'View Ticket Type')

@section('content')
    Name: {{ $ticketType->name }}<br/>
@endsection
