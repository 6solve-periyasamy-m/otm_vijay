@extends('layout.main')

@section('title', 'Update Activity Inventory Tours')

@section('content')
    @include('partials.models.activity_inventory_tours.form', ['action' => route('activity-inventory-tours.update', ['tour' => $tour, 'activityInventoryTour' => $activityInventoryTour,]),
      'tour_id' => $activityInventoryTour->tour_id,
      'activity_inventory_id' => $activityInventoryTour->activity_inventory_id,
      'tour_component_type' => $activityInventoryTour->tour_component_type,
      'tour_sales_price' => $activityInventoryTour->tour_sales_price,
    ])
@endsection
