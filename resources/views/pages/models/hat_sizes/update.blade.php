@extends('layout.main')

@section('title', 'Update Hat Sizes')

@section('content')
  @include('partials.models.hat_sizes.form', ['action' => route('hat-sizes.update', ['hatSize' => $hatSize,]),
    'name' => $hatSize->name,
  ])
@endsection
