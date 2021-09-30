@extends('layout.main')

@section('title', 'Update Travel Classes')

@section('content')
  @include('partials.models.travel_classes.form', ['action' => route('travel_classes.update', ['travelClass' => $travelClass,]),
    'title' => $travelClass->title,
  ])
@endsection
