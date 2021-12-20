@extends('layout.form', ['action' => route('transports.store'),'multipart' => true,])

@section('title', 'Create Transport')

@section('form-body')
    @include('partials.models.transports.form')
@endsection
