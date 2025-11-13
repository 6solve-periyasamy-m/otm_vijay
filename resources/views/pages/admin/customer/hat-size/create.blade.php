@extends('layout.form', ['action' => route('hat-sizes.store'),])

@section('title', 'Create Hat Size')

@section('form-body')
    @include('partials.models.hat_sizes.form')
@endsection
