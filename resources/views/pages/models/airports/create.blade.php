@extends('layout.master')

@section('title', 'Create Airport')

@section('content')
    @include('partials.models.airports.form', ['action' => route('airports.store'),])
@endsection
