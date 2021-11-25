@extends('layout.form', ['action' => route('travel-classes.store'),])

@section('title', 'Create Travel Class')

@section('form-body')
    @include('partials.models.travel_classes.form')
@endsection
