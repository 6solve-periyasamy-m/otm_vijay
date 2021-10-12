@extends('layout.main')

@section('title', 'Create Flight Inventory Tours')

@section('content')
    @include('partials.models.flight_inventory_tours.form', ['action' => route('flight-inventory-tours.store'),])
@endsection
