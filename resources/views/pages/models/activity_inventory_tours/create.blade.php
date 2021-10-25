@extends('layout.main')

@section('title', 'Create Activity Inventory Tour')

@section('content')
    @include('partials.models.activity_inventory_tours.form', ['action' => route('activity-inventory-tours.store', ['tour' => $tour, ]),])
@endsection
