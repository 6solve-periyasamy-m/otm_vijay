@extends('layout.form', ['action' => route('regions.store'),])

@section('title', 'Create Region')

@section('form-body')
    @include('partials.models.regions.form')
@endsection
