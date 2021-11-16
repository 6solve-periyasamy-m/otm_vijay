@extends('layout.form', ['action' => route('locations.update', ['location' => $location,]),])

@section('title', 'Update Location')

@section('form-body')
    @include('partials.models.locations.form', [
      'region_id' => $location->region_id,
      'location_type_id' => $location->location_type_id,
      'name' => $location->name,
      'address' => $location->address,
    ])
@endsection
