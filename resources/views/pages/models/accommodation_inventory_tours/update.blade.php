@extends('layout.form', ['action' => route('accommodation-inventory-tours.update', ['tour' => $tour, 'accommodationInventoryTour' => $accommodationInventoryTour,]),])

@section('title', 'Update Accommodation Inventory Tour')

@section('form-body')
    @include('partials.models.accommodation_inventory_tours.form', ['accommodationInventoryTour' => $accommodationInventoryTour,])
@endsection
