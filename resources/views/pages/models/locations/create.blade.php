@extends('layout.main')

@section('title', 'Create Locations')

@section('content')
  @include('partials.models.locations.form', ['action' => route('locations.store'),])
@endsection
