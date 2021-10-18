@extends('layout.main')

@section('title', 'Create Flight Inventory Tour')

@section('content')
    @include('partials.models.flight_inventory_tours.form', ['action' => route('flight-inventory-tours.store', ['tour' => $tour, ]),])
@endsection
