@extends('layout.main')

@section('title', 'Update Accommodation Inventory Tours')

@section('content')
  @include('partials.models.accommodation_inventory_tours.form', ['action' => route('accommodation_inventory_tours.update', ['accommodationInventoryTour' => $accommodationInventoryTour,]),
    'tour_id' => $accommodationInventoryTour->tour_id,
    'accommodation_inventory_id' => $accommodationInventoryTour->accommodation_inventory_id,
    'tour_component_type' => $accommodationInventoryTour->tour_component_type,
    'tour_sales_price' => $accommodationInventoryTour->tour_sales_price,
  ])
@endsection
