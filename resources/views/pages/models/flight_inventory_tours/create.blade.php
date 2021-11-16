@extends('layout.form', ['action' => route('flight-inventory-tours.store', ['tour' => $tour, ]),])

@section('title', 'Create Flight Inventory Tour')

@section('form-body')
    @include('partials.models.flight_inventory_tours.form')
@endsection
