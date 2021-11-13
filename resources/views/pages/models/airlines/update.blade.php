@extends('layout.form', ['action' => route('airlines.update', ['airline' => $airline,]),])

@section('title', 'Update Airline')

@section('form-body')
    @include('partials.models.airlines.form', [
      'name' => $airline->name,
    ])
@endsection
