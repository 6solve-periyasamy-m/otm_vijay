@extends('layout.master')

@section('title', 'Update Airport')

@section('content')
    @include('partials.models.airports.form', ['action' => route('airports.update', ['airport' => $airport,]),
      'name' => $airport->name,
      'iata_code' => $airport->iata_code,
    ])
@endsection
