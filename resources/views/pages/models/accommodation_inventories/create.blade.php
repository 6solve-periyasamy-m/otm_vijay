@extends('layout.main')

@section('title', 'Create Accommodation Inventories')

@section('content')
  @include('partials.models.accommodation_inventories.form', ['action' => route('accommodation_inventories.store'),])
@endsection
