@extends('layout.main')

@section('title', 'Update Airline')

@section('content')
    @include('partials.models.airlines.form', ['action' => route('airlines.update', ['airline' => $airline,]),
      'name' => $airline->name,
    ])
@endsection
