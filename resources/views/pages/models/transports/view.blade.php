@extends('layout.main')

@section('title', 'View Transport')

@section('content')
    Transport Type Id: {{ $transport->transport_type_id }}<br/>
    Operator Id: {{ $transport->operator_id }}<br/>
    Departure Location Id: {{ $transport->departure_location_id }}<br/>
    Arrival Location Id: {{ $transport->arrival_location_id }}<br/>
    Name: {{ $transport->name }}<br/>
    Description: {{ $transport->description }}<br/>
    Currency: {{ $transport->currency }}<br/>
    Is Domestic: {{ $transport->is_domestic }}<br/>
    Notes: {{ $transport->notes }}<br/>
@endsection
