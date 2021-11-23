@extends('layout.form', ['action' => route('activity-inventory-tours.store', ['tour' => $tour, ]),])

@section('title', 'Create Activity Inventory Tour')

@section('form-body')
    @include('partials.models.activity_inventory_tours.form')
@endsection
