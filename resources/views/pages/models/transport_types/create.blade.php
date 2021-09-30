@extends('layout.main')

@section('title', 'Create Transport Types')

@section('content')
  @include('partials.models.transport_types.form', ['action' => route('transport_types.store'),])
@endsection
