@extends('layout.main')

@section('title', 'Create Accommodations')

@section('content')
  @include('partials.models.accommodations.form', ['action' => route('accommodations.store'),])
@endsection
