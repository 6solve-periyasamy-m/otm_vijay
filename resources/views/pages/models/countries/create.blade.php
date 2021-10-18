@extends('layout.main')

@section('title', 'Create Country')

@section('content')
    @include('partials.models.countries.form', ['action' => route('countries.store'),])
@endsection
