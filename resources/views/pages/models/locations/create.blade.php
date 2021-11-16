@extends('layout.form', ['action' => route('locations.store'),])

@section('title', 'Create Location')

@section('form-body')
    @include('partials.models.locations.form')
@endsection
