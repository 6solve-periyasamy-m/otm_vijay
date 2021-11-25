@extends('layout.form', ['action' => route('accommodation-inventory-tours.store', ['tour' => $tour, ]),])

@section('title', 'Create Accommodation Inventory Tour')

@section('form-body')
    @include('partials.models.accommodation_inventory_tours.form')
@endsection
