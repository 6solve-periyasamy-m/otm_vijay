@extends('layout.form', ['action' => route('flights.update', ['flight' => $flight,]),'multipart' => true,])

@section('title', 'Update Flight')

@section('form-body')
    @include('partials.models.flights.form', [
      'airline_id' => $flight->airline_id,
      'departure_airport_id' => $flight->departure_airport_id,
      'arrival_airport_id' => $flight->arrival_airport_id,
      'is_domestic' => $flight->is_domestic,
      'notes' => $flight->notes,
      'currency' => $flight->currency_id,
      'available_after' => $flight->available_after,
    ])
@endsection
