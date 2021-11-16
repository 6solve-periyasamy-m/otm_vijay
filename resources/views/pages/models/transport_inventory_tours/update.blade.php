@extends('layout.form', ['action' => route('transport-inventory-tours.update', ['tour' => $tour, 'transportInventoryTour' => $transportInventoryTour,]),])

@section('title', 'Update Transport Inventory Tour')

@section('form-body')
    @include('partials.models.transport_inventory_tours.form', [
      'transport_inventory_id' => $transportInventoryTour->transport_inventory_id,
      'tour_component_type' => $transportInventoryTour->tour_component_type,
      'tour_sales_price' => $transportInventoryTour->tour_sales_price,
    ])
@endsection
