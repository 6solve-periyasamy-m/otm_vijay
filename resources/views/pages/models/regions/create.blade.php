@extends('layout.master')

@section('title', 'Create Region')

@section('content')
    @include('partials.models.regions.form', ['action' => route('regions.store'),])
@endsection
