@extends('layout.main')

@section('title', 'Create T Shirt Sizes')

@section('content')
  @include('partials.models.t_shirt_sizes.form', ['action' => route('t_shirt_sizes.store'),])
@endsection
