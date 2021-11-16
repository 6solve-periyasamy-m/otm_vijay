@extends('layout.form', ['action' => route('countries.store'),])

@section('title', 'Create Country')

@section('form-body')
    @include('partials.models.countries.form')
@endsection
