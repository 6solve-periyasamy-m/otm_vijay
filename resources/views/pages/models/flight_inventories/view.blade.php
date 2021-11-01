@extends('layout.main')

@section('title', 'View Flight Inventory')

@section('content')
    Flight Id: {{ $flightInventory->flight_id }}<br/>
    Travel Class Id: {{ $flightInventory->travel_class_id }}<br/>
    Flight Number: {{ $flightInventory->flight_number }}<br/>
    Check In Date Time: {{ $flightInventory->check_in }}<br/>
    Departure Date Time: {{ $flightInventory->departure_date_time }}<br/>
    Arrival Date Time: {{ $flightInventory->arrival_date_time }}<br/>
    Fit Selectable: {{ $flightInventory->fit_selectable }}<br/>
    Stock: {{ $flightInventory->stock }}<br/>
    Purchase Price: {{ $flightInventory->purchase_price }}<br/>
    Sales Price: {{ $flightInventory->sales_price }}<br/>
    Currency: {{ $flightInventory->currency }}<br/>
    Notes: {{ $flightInventory->notes }}<br/>
@endsection
