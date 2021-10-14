@extends('layout.main')

@section('title', 'Update Transport Inventory Tours')

@section('content')
    @include('partials.models.transport_inventory_tours.form', ['action' => route('transport-inventory-tours.update', ['transportInventoryTour' => $transportInventoryTour,]),
      'tour_id' => $transportInventoryTour->tour_id,
      'transport_inventory_id' => $transportInventoryTour->transport_inventory_id,
            'tour_component_type' => $accommodationInventoryTour->tour_component_type,
      'tour_sales_price' => $accommodationInventoryTour->tour_sales_price,
    ])
@endsection
