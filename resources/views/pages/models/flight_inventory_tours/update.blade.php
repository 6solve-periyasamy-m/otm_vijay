@extends('layout.form', ['action' => route('flight-inventory-tours.update', ['tour' => $tour, 'flightInventoryTour' => $flightInventoryTour,]),])

@section('title', 'Update Flight Inventory Tour')

@section('form-body')
    @include('partials.models.flight_inventory_tours.form', ['flightInventoryTour' => $flightInventoryTour,])
@endsection
