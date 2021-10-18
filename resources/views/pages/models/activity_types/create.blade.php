@extends('layout.main')

@section('title', 'Create Activity Type')

@section('content')
    @include('partials.models.activity_types.form', ['action' => route('activity-types.store'),])
@endsection
