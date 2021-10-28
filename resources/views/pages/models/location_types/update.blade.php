@extends('layout.master')

@section('title', 'Update Location Type')

@section('content')
    @include('partials.models.location_types.form', ['action' => route('location-types.update', ['locationType' => $locationType,]),
      'name' => $locationType->name,
    ])
@endsection
