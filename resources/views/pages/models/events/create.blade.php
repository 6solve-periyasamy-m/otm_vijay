@extends('layout.main')

@section('title', 'Create Event')

@section('content')
    @include('partials.models.events.form', ['action' => route('events.store'),])
@endsection
