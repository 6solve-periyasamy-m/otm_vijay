@extends('layout.form', ['action' => route('t-shirt-sizes.update', ['tShirtSize' => $tShirtSize,]),])

@section('title', 'Update T Shirt Size')

@section('form-body')
    @include('partials.models.t_shirt_sizes.form', [
      'name' => $tShirtSize->name,
    ])
@endsection
