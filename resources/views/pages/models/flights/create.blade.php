@extends('layout.form', ['action' => route('flights.store'),])

@section('title', 'Create Flight')

@section('form-body')
    @include('partials.models.flights.form')
@endsection
