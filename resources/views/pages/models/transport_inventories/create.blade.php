@extends('layout.main')

@section('title', 'Create Transport Inventory')

@section('content')
    @include('partials.models.transport_inventories.form', ['action' => route('transport-inventories.store', ['transport' => $transport, ]),])
@endsection
