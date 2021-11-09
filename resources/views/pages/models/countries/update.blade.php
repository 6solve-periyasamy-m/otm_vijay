@extends('layout.master')

@section('title', 'Update Country')

@section('content')
    @include('partials.models.countries.form', ['action' => route('countries.update', ['country' => $country,]),
        'country' => $country,
        'name' => $country->name,
        'numeric_code' => $country->numeric_code,
        'alpha_code' => $country->alpha_code,
        'dialing_code' => $country->dialing_code,
    ])
@endsection
