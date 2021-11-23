@extends('layout.form', ['action' => route('location-types.store'),])

@section('title', 'Create Location Type')

@section('form-body')
    @include('partials.models.location_types.form')
@endsection
