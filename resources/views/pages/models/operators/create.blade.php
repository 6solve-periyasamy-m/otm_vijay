@extends('layout.main')

@section('title', 'Create Operators')

@section('content')
  @include('partials.models.operators.form', ['action' => route('operators.store'),])
@endsection
