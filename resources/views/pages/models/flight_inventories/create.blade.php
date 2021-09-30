@extends('layout.main')

@section('title', 'Create Flight Inventories')

@section('content')
  @include('partials.models.flight_inventories.form', ['action' => route('flight_inventories.store'),])
@endsection
