@extends('layout.master')

@section('title', 'Create Flight')

@section('content')
    @include('partials.models.flights.form', ['action' => route('flights.store'),])
@endsection
