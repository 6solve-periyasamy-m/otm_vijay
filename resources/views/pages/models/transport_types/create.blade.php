@extends('layout.form', ['action' => route('transport-types.store'),])

@section('title', 'Create Transport Type')

@section('form-body')
    @include('partials.models.transport_types.form')
@endsection
