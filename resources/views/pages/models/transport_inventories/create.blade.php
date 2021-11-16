@extends('layout.form', ['action' => route('transport-inventories.store', ['transport' => $transport, ]),])

@section('title', 'Create Transport Inventory')

@section('form-body')
    @include('partials.models.transport_inventories.form')
@endsection
