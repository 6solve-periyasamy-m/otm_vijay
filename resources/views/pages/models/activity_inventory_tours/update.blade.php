@extends('layout.form', ['action' => route('activity-inventory-tours.update', ['tour' => $tour, 'activityInventoryTour' => $activityInventoryTour, ]),])

@section('title', 'Update Activity Inventory Tour')

@section('form-body')
    @include('partials.models.activity_inventory_tours.form', [
      'tour_id' => $activityInventoryTour->tour_id,
      'activity_inventory_id' => $activityInventoryTour->activity_inventory_id,
      'tour_component_type' => $activityInventoryTour->tour_component_type,
      'tour_sales_price' => $activityInventoryTour->tour_sales_price,
    ])
@endsection
