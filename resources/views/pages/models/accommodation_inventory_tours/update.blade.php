@extends('layout.main')

@section('title', 'Update Accommodation Inventory Tour')

@section('content')
    @include('partials.models.accommodation_inventory_tours.form', ['action' => route('accommodation-inventory-tours.update', ['tour' => $tour, 'accommodationInventoryTour' => $accommodationInventoryTour,]),
      'accommodation_inventory_id' => $accommodationInventoryTour->accommodation_inventory_id,
      'tour_component_type' => $accommodationInventoryTour->tour_component_type,
      'tour_sales_price' => $accommodationInventoryTour->tour_sales_price,
    ])
@endsection
