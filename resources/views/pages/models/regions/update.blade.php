@extends('layout.form', ['action' => route('regions.update', ['region' => $region,]),])

@section('title', 'Update Region')

@section('form-body')
    @include('partials.models.regions.form', [
      'country_id' => $region->country_id,
      'name' => $region->name,
    ])
@endsection
