@extends('layout.form', ['action' => route('t-shirt-sizes.store'),])

@section('title', 'Create T Shirt Size')

@section('form-body')
    @include('partials.models.t_shirt_sizes.form')
@endsection
