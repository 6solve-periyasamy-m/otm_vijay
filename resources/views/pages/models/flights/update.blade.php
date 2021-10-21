@extends('layout.main')

@section('title', 'Update Flight')

@section('content')
    @include('partials.models.flights.form', ['action' => route('flights.update', ['flight' => $flight,]),
      'airline_id' => $flight->airline_id,
      'departure_airport_id' => $flight->departure_airport_id,
      'arrival_airport_id' => $flight->arrival_airport_id,
      'is_domestic' => $flight->is_domestic,
      'notes' => $flight->notes,
      'available_after' => $flight->available_after,
    ])
@endsection
