@extends('layout.form', ['action' => route('events.store'),])

@section('title', 'Create Event')

@section('form-body')
    @include('partials.models.events.form')
@endsection
