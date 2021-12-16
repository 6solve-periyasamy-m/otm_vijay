@extends('layout.form', ['action' => route('tour-categories.store'),])

@section('title', 'Create Tour Category')

@section('form-body')
    @include('partials.models.tour_categories.form')
@endsection
