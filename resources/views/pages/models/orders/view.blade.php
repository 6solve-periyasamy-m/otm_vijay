@extends('layout.main')

@section('title', 'View Order')

@section('content')
    Quote Id: {{ $order->quote_id }}<br/>
    Tour Id: {{ $order->tour_id }}<br/>
    Lead Booker Id: {{ $order->lead_booker_id }}<br/>
    Token: {{ $order->token }}<br/>
    Booking Reference: {{ $order->booking_reference }}<br/>
    Ordered On: {{ $order->ordered_on }}<br/>
    Internal Notes: {{ $order->internal_notes }}<br/>
    External Notes: {{ $order->external_notes }}<br/>
@endsection
