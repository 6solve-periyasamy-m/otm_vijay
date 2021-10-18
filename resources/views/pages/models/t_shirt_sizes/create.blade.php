@extends('layout.main')

@section('title', 'Create T Shirt Size')

@section('content')
    @include('partials.models.t_shirt_sizes.form', ['action' => route('t-shirt-sizes.store'),])
@endsection
