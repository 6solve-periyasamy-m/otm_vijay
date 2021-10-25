@extends('layout.main')

@section('title', 'Create Travel Class')

@section('content')
    @include('partials.models.travel_classes.form', ['action' => route('travel-classes.store'),])
@endsection
