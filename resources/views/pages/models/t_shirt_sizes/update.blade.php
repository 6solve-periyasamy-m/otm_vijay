@extends('layout.main')

@section('title', 'Update T Shirt Sizes')

@section('content')
  @include('partials.models.t_shirt_sizes.form', ['action' => route('t-shirt-sizes.update', ['tShirtSize' => $tShirtSize,]),
    'name' => $tShirtSize->name,
  ])
@endsection
