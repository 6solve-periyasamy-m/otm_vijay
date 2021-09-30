@extends('layout.main')

@section('title', 'Create Flights')

@section('content')
  @include('partials.models.flights.form', ['action' => route('flights.store'),])
@endsection
