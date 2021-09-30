@extends('layout.main')

@section('title', 'Update Regions')

@section('content')
  @include('partials.models.regions.form', ['action' => route('regions.update', ['region' => $region,]),
    'country_id' => $region->country_id,
    'name' => $region->name,
  ])
@endsection
