@extends('layout.main')

@section('title', 'Update Flight Inventories')

@section('content')
    @include('partials.models.flight_inventories.form', ['action' => route('flight-inventories.update', ['flight' => $flight, 'flightInventory' => $flightInventory,]),
      'flight_id' => $flightInventory->flight_id,
      'travel_class_id' => $flightInventory->travel_class_id,
      'flight_number' => $flightInventory->flight_number,
      'check_in_date_time' => $flightInventory->check_in_date_time,
      'departure_date_time' => $flightInventory->departure_date_time,
      'arrival_date_time' => $flightInventory->arrival_date_time,
      'fit_selectable' => $flightInventory->fit_selectable,
      'stock' => $flightInventory->stock,
      'purchase_price' => $flightInventory->purchase_price,
      'sales_price' => $flightInventory->sales_price,
      'currency' => $flightInventory->currency,
      'notes' => $flightInventory->notes,
    ])
@endsection
