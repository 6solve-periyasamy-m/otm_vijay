@extends('layout.main')

@section('title', 'Update Location Types')

@section('content')
  @include('partials.models.location_types.form', ['action' => route('location_types.update', ['locationType' => $locationType,]),
    'name' => $locationType->name,
  ])
@endsection
