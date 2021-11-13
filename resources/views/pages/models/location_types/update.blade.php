@extends('layout.form', ['action' => route('location-types.update', ['locationType' => $locationType,]),])

@section('title', 'Update Location Type')

@section('form-body')
    @include('partials.models.location_types.form', [
      'name' => $locationType->name,
    ])
@endsection
