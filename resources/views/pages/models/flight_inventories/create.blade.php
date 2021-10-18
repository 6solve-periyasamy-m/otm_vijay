@extends('layout.main')

@section('title', 'Create Flight Inventory')

@section('content')
    @include('partials.models.flight_inventories.form', ['action' => route('flight-inventories.store', ['flight' => $flight, ]),])
@endsection
