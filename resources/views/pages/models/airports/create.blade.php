@extends('layout.main')

@section('title', 'Create Airports')

@section('content')
    @include('partials.models.airports.form', ['action' => route('airports.store'),])
@endsection
