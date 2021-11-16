@extends('layout.form', ['action' => route('airports.store'),])

@section('title', 'Create Airport')

@section('form-body')
    @include('partials.models.airports.form')
@endsection
