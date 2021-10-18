@extends('layout.main')

@section('title', 'Create Accommodation')

@section('content')
    @include('partials.models.accommodations.form', ['action' => route('accommodations.store'),])
@endsection
