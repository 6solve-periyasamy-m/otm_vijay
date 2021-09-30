@extends('layout.main')

@section('title', 'Create Events')

@section('content')
  @include('partials.models.events.form', ['action' => route('events.store'),])
@endsection
