@extends('layout.main')

@section('title', 'Create Tours')

@section('content')
  @include('partials.models.tours.form', ['action' => route('tours.store'),])
@endsection
