@extends('layout.main')

@section('title', 'Create Countries')

@section('content')
  @include('partials.models.countries.form', ['action' => route('countries.store'),])
@endsection
