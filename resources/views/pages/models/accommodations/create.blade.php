@extends('layout.form', ['action' => route('accommodations.store'),])

@section('title', 'Create Accommodation')

@section('form-body')
    @include('partials.models.accommodations.form')
@endsection
