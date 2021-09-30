@extends('layout.main')

@section('title', 'Update Airlines')

@section('content')
  @include('partials.models.airlines.form', ['action' => route('airlines.update', ['airline' => $airline,]),
    'name' => $airline->name,
  ])
@endsection
