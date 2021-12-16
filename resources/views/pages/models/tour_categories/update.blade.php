@extends('layout.form', ['action' => route('tour-categories.update', ['tourCategory' => $tourCategory,]),])

@section('title', 'Update Tour Category')

@section('form-body')
    @include('partials.models.tour_categories.form', [
      'name' => $tourCategory->name,
    ])
@endsection
