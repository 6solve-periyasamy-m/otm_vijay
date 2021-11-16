@extends('layout.form', ['action' => route('accommodation-inventories.store', ['accommodation' => $accommodation, ]),])

@section('title', 'Create Accommodation Inventory')

@section('form-body')
    @include('partials.models.accommodation_inventories.form')
@endsection
