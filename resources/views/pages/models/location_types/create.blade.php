@extends('layout.main')

@section('title', 'Create Location Types')

@section('content')
  @include('partials.models.location_types.form', ['action' => route('location_types.store'),])
@endsection
