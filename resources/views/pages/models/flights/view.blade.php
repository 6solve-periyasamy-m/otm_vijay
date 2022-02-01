@extends('layout.main')

@section('title', 'View Flight')

@section('content')
    Airline Id: {{ $flight->airline_id }}<br/>
    Departure Airport Id: {{ $flight->departure_airport_id }}<br/>
    Arrival Airport Id: {{ $flight->arrival_airport_id }}<br/>
    Is Domestic: {{ $flight->is_domestic }}<br/>
    Notes: {{ $flight->notes }}<br/>
    Available From: {{ $flight->available_from }}<br/>
@endsection
