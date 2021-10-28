@extends('layout.master')

@section('title', 'Update Location')

@section('content')
    @include('partials.models.locations.form', ['action' => route('locations.update', ['location' => $location,]),
      'region_id' => $location->region_id,
      'location_type_id' => $location->location_type_id,
      'name' => $location->name,
      'address' => $location->address,
    ])
@endsection
