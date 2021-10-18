@extends('layout.main')

@section('title', 'View Flight Inventory Tour')

@section('content')
    Tour Id: {{ $flightInventoryTour->tour_id }}<br/>
    Flight Inventory Id: {{ $flightInventoryTour->flight_inventory_id }}<br/>
    Tour Component Type: {{ $flightInventoryTour->tour_component_type }}<br/>
    Flight Type: {{ $flightInventoryTour->flight_type }}<br/>
    Tour Sales Price: {{ $flightInventoryTour->tour_sales_price }}<br/>
@endsection
