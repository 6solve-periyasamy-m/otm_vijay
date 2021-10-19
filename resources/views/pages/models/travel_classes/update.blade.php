@extends('layout.main')

@section('title', 'Update Travel Class')

@section('content')
    @include('partials.models.travel_classes.form', ['action' => route('travel-classes.update', ['travelClass' => $travelClass,]),
      'title' => $travelClass->title,
    ])
@endsection
