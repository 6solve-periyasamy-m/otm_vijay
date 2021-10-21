@extends('layout.main')

@section('title', 'Create Region')

@section('content')
    @include('partials.models.regions.form', ['action' => route('regions.store'),])
@endsection
