@extends('layout.form', ['action' => route('transports.store'),])

@section('title', 'Create Transport')

@section('form-body')
    @include('partials.models.transports.form')
@endsection
