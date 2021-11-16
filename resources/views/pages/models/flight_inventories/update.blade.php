@extends('layout.form', ['action' => route('flight-inventories.update', ['flight' => $flight, 'flightInventory' => $flightInventory,])])

@section('title', 'Update Flight Inventory')

@section('form-body')
    @include('partials.models.flight_inventories.form', [
      'flight_id' => $flightInventory->flight_id,
      'travel_class_id' => $flightInventory->travel_class_id,
      'flight_number' => $flightInventory->flight_number,
      'check_in' => $flightInventory->check_in,
      'departs_at' => $flightInventory->departs_at,
      'arrives_at' => $flightInventory->arrives_at,
      'fit_selectable' => $flightInventory->fit_selectable,
      'stock' => $flightInventory->stock,
      'purchase_price' => $flightInventory->purchase_price,
      'sales_price' => $flightInventory->sales_price,
      'currency' => $flightInventory->currency,
      'notes' => $flightInventory->notes,
    ])
@endsection
