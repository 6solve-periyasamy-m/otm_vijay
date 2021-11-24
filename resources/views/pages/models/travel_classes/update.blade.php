@extends('layout.form', ['action' => route('travel-classes.update', ['travelClass' => $travelClass,]),])

@section('title', 'Update Travel Class')

@section('form-body')
    @include('partials.models.travel_classes.form', [
      'name' => $travelClass->name,
    ])
@endsection
