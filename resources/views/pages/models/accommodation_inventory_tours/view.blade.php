@extends('layout.main')

@section('title', 'View Accommodation Inventory Tour')

@section('content')
    Tour Id: {{ $accommodationInventoryTour->tour_id }}<br/>
    Accommodation Inventory Id: {{ $accommodationInventoryTour->accommodation_inventory_id }}<br/>
    Tour Component Type: {{ $accommodationInventoryTour->tour_component_type }}<br/>
    Tour Sales Price: {{ $accommodationInventoryTour->tour_sales_price }}<br/>
@endsection
