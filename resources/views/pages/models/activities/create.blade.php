@extends('layout.main')

@section('title', 'Create Activities')

@section('content')
    @include('partials.models.activities.form', ['action' => route('activities.store'),])
@endsection
