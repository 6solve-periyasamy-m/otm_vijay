@extends('layout.main')

@section('title', 'Create Hat Sizes')

@section('content')
    @include('partials.models.hat_sizes.form', ['action' => route('hat-sizes.store'),])
@endsection
