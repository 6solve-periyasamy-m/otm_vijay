@extends('layout.main')

@section('title', 'View Transport Inventories')

@section('content')
    Transport Id: {{ $transportInventory->transport_id }}<br/>
    Travel Class Id: {{ $transportInventory->travel_class_id }}<br/>
    Departure Date Time: {{ $transportInventory->departure_date_time }}<br/>
    Departure Confirmed: {{ $transportInventory->departure_confirmed }}<br/>
    Arrival Date Time: {{ $transportInventory->arrival_date_time }}<br/>
    Arrival Confirmed: {{ $transportInventory->arrival_confirmed }}<br/>
    Fit Selectable: {{ $transportInventory->fit_selectable }}<br/>
    Stock: {{ $transportInventory->stock }}<br/>
    Purchase Price: {{ $transportInventory->purchase_price }}<br/>
    Sales Price: {{ $transportInventory->sales_price }}<br/>
    Currency: {{ $transportInventory->currency }}<br/>
    Notes: {{ $transportInventory->notes }}<br/>
@endsection
