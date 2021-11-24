@extends('layout.form', ['action' => route('tours.store'),])

@section('title', 'Create Tour')

@section('form-body')
    @include('partials.models.tours.form')
@endsection
