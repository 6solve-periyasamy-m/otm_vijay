@extends('layout.main')

@section('title', 'Create Transport')

@section('content')
    @include('partials.models.transports.form', ['action' => route('transports.store'),])
@endsection
