@extends('layout.main')

@section('title', 'View Activity Inventory Tour')

@section('content')
    Tour Id: {{ $activityInventoryTour->tour_id }}<br/>
    Activity Inventory Id: {{ $activityInventoryTour->activity_inventory_id }}<br/>
    Tour Component Type: {{ $activityInventoryTour->tour_component_type }}<br/>
    Tour Sales Price: {{ $activityInventoryTour->tour_sales_price }}<br/>
@endsection
