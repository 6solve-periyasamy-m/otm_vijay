@extends('layout.form', ['action' => route('flight-inventory-tours.update', ['tour' => $tour, 'flightInventoryTour' => $flightInventoryTour,]),])

@section('title', 'Update Flight Inventory Tour')

@section('form-body')
    @include('partials.models.flight_inventory_tours.form', [
      'tour_id' => $flightInventoryTour->tour_id,
      'flight_inventory_id' => $flightInventoryTour->flight_inventory_id,
      'tour_component_type' => $flightInventoryTour->tour_component_type,
      'flight_type' => $flightInventoryTour->flight_type,
      'tour_sales_price' => $flightInventoryTour->tour_sales_price,
    ])
@endsection
