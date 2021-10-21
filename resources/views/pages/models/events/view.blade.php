@extends('layout.main')

@section('title', 'View Event')

@section('content')
    Event Title: {{ $event->event_title }}<br/>
    Event Description: {{ $event->event_description }}<br/>
    Event Start Date: {{ $event->event_start_date }}<br/>
    Event End Date: {{ $event->event_end_date }}<br/>
    Booking Url: {{ $event->booking_url }}<br/>
    Notes: {{ $event->notes }}<br/>
@endsection
