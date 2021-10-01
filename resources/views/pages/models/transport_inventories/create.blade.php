@extends('layout.main')

@section('title', 'Create Transport Inventories')

@section('content')
    @include('partials.models.transport_inventories.form', ['action' => route('transport-inventories.store'),])
@endsection
