@extends('layout.form', ['action' => route('hat-sizes.update', ['hatSize' => $hatSize,]),])

@section('title', 'Update Hat Size')

@section('form-body')
    @include('partials.models.hat_sizes.form', [
      'name' => $hatSize->name,
    ])
@endsection
