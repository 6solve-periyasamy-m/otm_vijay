@extends('layout.main')

@section('title', 'Create Location Type')

@section('content')
    @include('partials.models.location_types.form', ['action' => route('location-types.store'),])
@endsection
