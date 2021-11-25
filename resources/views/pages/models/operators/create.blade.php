@extends('layout.form', ['action' => route('operators.store'),])

@section('title', 'Create Operator')

@section('form-body')
    @include('partials.models.operators.form')
@endsection
