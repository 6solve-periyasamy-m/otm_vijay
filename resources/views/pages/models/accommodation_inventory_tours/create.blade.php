@extends('layout.main')

@section('title', 'Create Accommodation Inventory Tour')

@section('content')
    @include('partials.models.accommodation_inventory_tours.form', ['action' => route('accommodation-inventory-tours.store', ['tour' => $tour, ]),])
@endsection
