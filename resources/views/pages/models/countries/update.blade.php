@extends('layout.form', ['action' => route('countries.update', ['country' => $country,]),])

@section('title', 'Update Country')

@section('form-body')
    @include('partials.models.countries.form', [
        'country' => $country,
        'name' => $country->name,
        'numeric_code' => $country->numeric_code,
        'alpha_code' => $country->alpha_code,
        'dialing_code' => $country->dialing_code,
    ])
@endsection
