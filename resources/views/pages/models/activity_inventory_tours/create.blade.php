@extends('layout.main')

@section('title', 'Create Activity Inventory Tours')

@section('content')
    @include('partials.models.activity_inventory_tours.form', ['action' => route('activity-inventory-tours.store'),])
@endsection
