@extends('layout.main')

@section('title', 'Create Transport Inventory Tours')

@section('content')
  @include('partials.models.transport_inventory_tours.form', ['action' => route('transport-inventory-tours.store'),])
@endsection
