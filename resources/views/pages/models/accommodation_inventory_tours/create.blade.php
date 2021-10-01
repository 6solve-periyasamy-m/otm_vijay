@extends('layout.main')

@section('title', 'Create Accommodation Inventory Tours')

@section('content')
  @include('partials.models.accommodation_inventory_tours.form', ['action' => route('accommodation-inventory-tours.store'),])
@endsection
