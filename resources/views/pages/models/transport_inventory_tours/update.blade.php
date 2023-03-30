@extends('layout.form', ['action' => route('transport-inventory-tours.update', ['tour' => $tour, 'transportInventoryTour' => $transportInventoryTour,]),])

@section('title', 'Update Transport Inventory Tour')

@section('form-body')
    @include('partials.models.transport_inventory_tours.form', ['transportInventoryTour' => $transportInventoryTour,])
@endsection
