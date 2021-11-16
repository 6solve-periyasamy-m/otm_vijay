@extends('layout.form', ['action' => route('accommodation-inventory-tours.update', ['tour' => $tour, 'accommodationInventoryTour' => $accommodationInventoryTour,]),])

@section('title', 'Update Accommodation Inventory Tour')

@section('form-body')
    @include('partials.models.accommodation_inventory_tours.form', [
      'accommodation_inventory_id' => $accommodationInventoryTour->accommodation_inventory_id,
      'tour_component_type' => $accommodationInventoryTour->tour_component_type,
      'tour_sales_price' => $accommodationInventoryTour->tour_sales_price,
    ])
@endsection
