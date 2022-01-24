@extends('layout.form', ['action' => route('activities.store'), 'multipart' => true,])

@section('title', 'Create Activity')

@section('form-body')
    @include('partials.models.activities.form')
@endsection
