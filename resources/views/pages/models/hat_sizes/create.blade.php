@extends('layout.master')

@section('title', 'Create Hat Size')

@section('content')
    @include('partials.models.hat_sizes.form', ['action' => route('hat-sizes.store'),])
@endsection
