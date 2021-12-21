@extends('layout.form', ['action' => route('accommodations.store'), 'multipart' => true,])

@section('title', 'Create Accommodation')

@section('form-body')
    @include('partials.models.accommodations.form')
@endsection
