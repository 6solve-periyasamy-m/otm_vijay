@extends('layout.main')

@section('title', 'View Event')

@section('content')
    Event Title: {{ $event->name }}<br/>
    Event Description: {{ $event->description }}<br/>
    Event Start Date: {{ $event->starts_at }}<br/>
    Event End Date: {{ $event->ends_at }}<br/>
    Booking Url: {{ $event->booking_url }}<br/>
    Notes: {{ $event->notes }}<br/>
@endsection
