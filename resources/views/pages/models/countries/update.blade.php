@extends('layout.master')

@section('title', 'Update Country')

@section('content')
    @include('partials.models.countries.form', ['action' => route('countries.update', ['country' => $country,]),
      'name' => $country->name,
      'code' => $country->code,
      'currency' => $country->currency,
    ])
@endsection
