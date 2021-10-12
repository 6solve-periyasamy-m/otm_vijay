@extends('layout.main')

@section('title', 'Update Location Types')

@section('content')
    @include('partials.models.location_types.form', ['action' => route('location-types.update', ['locationType' => $locationType,]),
      'name' => $locationType->name,
    ])
@endsection
