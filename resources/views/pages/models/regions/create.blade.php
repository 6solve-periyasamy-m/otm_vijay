@extends('layout.main')

@section('title', 'Create Regions')

@section('content')
  @include('partials.models.regions.form', ['action' => route('regions.store'),])
@endsection
