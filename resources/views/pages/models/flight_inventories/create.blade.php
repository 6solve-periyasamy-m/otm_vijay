@extends('layout.form', ['action' => route('flight-inventories.store', ['flight' => $flight, ]),])

@section('title', 'Create Flight Inventory')

@section('form-body')
    @include('partials.models.flight_inventories.form')
@endsection
