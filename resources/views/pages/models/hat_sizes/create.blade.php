@extends('layout.main')

@section('title', 'Create Hat Sizes')

@section('content')
  @include('partials.models.hat_sizes.form', ['action' => route('hat_sizes.store'),])
@endsection
