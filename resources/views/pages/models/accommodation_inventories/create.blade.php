@extends('layout.main')

@section('title', 'Create Accommodation Inventory')

@section('content')
    @include('partials.models.accommodation_inventories.form', ['action' => route('accommodation-inventories.store', ['accommodation' => $accommodation, ]),])
@endsection
