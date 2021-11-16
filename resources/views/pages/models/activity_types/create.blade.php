@extends('layout.form', ['action' => route('activity-types.store'),])

@section('title', 'Create Activity Type')

@section('form-body')
    @include('partials.models.activity_types.form')
@endsection
