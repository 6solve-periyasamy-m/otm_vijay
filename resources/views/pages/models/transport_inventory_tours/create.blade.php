@extends('layout.form', ['action' => route('transport-inventory-tours.store', ['tour' => $tour, ]),])

@section('title', 'Create Transport Inventory Tour')

@section('form-body')
    @include('partials.models.transport_inventory_tours.form')
@endsection
