@extends('layout.form', ['action' => route('countries.update', ['country' => $country,]),])

@section('title', 'Update Country')

@section('form-body')
    @include('partials.models.countries.form', [
      'name' => $country->name,
      'code' => $country->code,
      'currency' => $country->currency,
    ])
@endsection
