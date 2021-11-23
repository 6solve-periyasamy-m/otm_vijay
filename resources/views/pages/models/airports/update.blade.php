@extends('layout.form', ['action' => route('airports.update', ['airport' => $airport,]),])

@section('title', 'Update Airport')

@section('form-body')
    @include('partials.models.airports.form', [
      'name' => $airport->name,
      'iata_code' => $airport->iata_code,
    ])
@endsection
