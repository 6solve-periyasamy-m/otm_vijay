@extends('layout.master')

@section('title', 'Create Location')

@section('content')
    @include('partials.models.locations.form', ['action' => route('locations.store'),])
@endsection
