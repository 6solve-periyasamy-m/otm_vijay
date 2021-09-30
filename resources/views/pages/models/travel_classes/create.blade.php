@extends('layout.main')

@section('title', 'Create Travel Classes')

@section('content')
  @include('partials.models.travel_classes.form', ['action' => route('travel_classes.store'),])
@endsection
